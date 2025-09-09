console.log("Starting server initialization...");

// Load environment variables from .env file
import 'dotenv/config';

import express, { type Request, Response, NextFunction } from "express";
import session from "express-session";
import fileUpload from "express-fileupload";
import path from "path";
import fs from "fs";
import { registerRoutes } from "./routes";
import { serveStatic, log } from "./vite";
console.log("Imports loaded successfully");

const app = express();
app.use(express.json());
app.use(express.urlencoded({ extended: false }));

// Create uploads directory if it doesn't exist
const uploadsDir = path.join(process.cwd(), 'uploads');
if (!fs.existsSync(uploadsDir)) {
  fs.mkdirSync(uploadsDir, { recursive: true });
}

// Create assets directory structure
const assetsDir = path.join(uploadsDir, 'assets');
const appAssetsDir = path.join(assetsDir, 'App');
if (!fs.existsSync(appAssetsDir)) {
  fs.mkdirSync(appAssetsDir, { recursive: true });
}

// File upload middleware
app.use(fileUpload({
  limits: { fileSize: 50 * 1024 * 1024 }, // 50MB max file size
  useTempFiles: true,
  tempFileDir: path.join(uploadsDir, 'temp'),
  createParentPath: true,
  abortOnLimit: true,
  responseOnLimit: "File size limit exceeded",
}));

// Serve uploaded files statically
app.use('/uploads', express.static(uploadsDir));

// Session configuration
app.use(session({
  secret: process.env.SESSION_SECRET || 'your-secret-key-here',
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: false, // Set to true in production with HTTPS
    httpOnly: true,
    maxAge: 24 * 60 * 60 * 1000 // 24 hours
  }
}));

app.use((req, res, next) => {
  const start = Date.now();
  const path = req.path;
  let capturedJsonResponse: Record<string, any> | undefined = undefined;

  const originalResJson = res.json;
  res.json = function (bodyJson, ...args) {
    capturedJsonResponse = bodyJson;
    return originalResJson.apply(res, [bodyJson, ...args]);
  };

  res.on("finish", () => {
    const duration = Date.now() - start;
    if (path.startsWith("/api")) {
      let logLine = `${req.method} ${path} ${res.statusCode} in ${duration}ms`;
      if (capturedJsonResponse) {
        logLine += ` :: ${JSON.stringify(capturedJsonResponse)}`;
      }

      if (logLine.length > 80) {
        logLine = logLine.slice(0, 79) + "…";
      }

      log(logLine);
    }
  });

  next();
});

(async () => {
  console.log("Starting server setup...");
  const server = await registerRoutes(app);
  console.log("Routes registered successfully");

  // Initialize demo users
  console.log("Initializing demo users...");
  try {
    const { MySQLStorage } = await import("./mysql-storage");
    const mysqlStorage = new MySQLStorage();
    await mysqlStorage.initializeDemoUsers();
    console.log("Demo users initialization complete");
  } catch (error) {
    console.error("Demo users initialization failed:", error);
  }

  app.use((err: any, _req: Request, res: Response, _next: NextFunction) => {
    const status = err.status || err.statusCode || 500;
    const message = err.message || "Internal Server Error";

    res.status(status).json({ message });
    throw err;
  });

  // importantly only setup vite in development and after
  // setting up all the other routes so the catch-all route
  // doesn't interfere with the other routes
  console.log("Environment:", app.get("env"));
  if (app.get("env") === "development") {
    console.log("Setting up Vite...");
    const { setupVite } = await import("./vite");
    await setupVite(app, server);
    console.log("Vite setup complete");
  } else {
    console.log("Setting up static serving...");
    serveStatic(app);
  }

  // ALWAYS serve the app on the port specified in the environment variable PORT
  // Other ports are firewalled. Default to 5000 if not specified.
  // this serves both the API and the client.
  // It is the only port that is not firewalled.
  const port = parseInt(process.env.PORT || '5000', 10);
  console.log(`Attempting to start server on port ${port}...`);
  server.listen(port, () => {
    log(`serving on port ${port}`);
  });
})();
