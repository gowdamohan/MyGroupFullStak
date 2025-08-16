# Overview

AppHub is a mobile-first web platform designed to provide users with a centralized hub for discovering and managing various applications. The platform features a modern, mobile-optimized interface with user authentication, app management capabilities, and a comprehensive design system. Built as a full-stack application, it combines a React frontend with an Express.js backend and PostgreSQL database integration.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Frontend Architecture

The client-side application is built using **React 18** with **TypeScript** and follows a component-based architecture. The application uses **Vite** as the build tool for fast development and optimized production builds.

### UI Framework
- **shadcn/ui components** with **Radix UI primitives** for accessible, customizable UI components
- **Tailwind CSS** for utility-first styling with custom design tokens
- **Bootstrap 5** for additional responsive utilities and layout components
- **CSS variables** for consistent theming and color management

### State Management
- **TanStack Query (React Query)** for server state management and data fetching
- **React Hook Form** with **Zod validation** for form handling and validation
- Local component state using React hooks for UI interactions

### Routing & Navigation
- **Wouter** for lightweight client-side routing
- Mobile-first navigation with bottom tab bar and modal overlays

### Mobile Design Pattern
The application implements a mobile-first design with:
- Header with logo, search, and profile sections
- Grid-based app launcher with colorful icons
- Auto-rotating advertisement carousel
- Bottom navigation for core app sections
- Modal-based authentication flows

## Backend Architecture

The server is built using **Express.js** with **TypeScript** in ESM module format, providing a RESTful API architecture with dual-layer design:

### Database Layer
- **In-memory SQLite** for development (with fallback to file-based SQLite)
- **MySQL integration** available via backend/config/database.js with full MVC architecture
- **bcryptjs** for secure password hashing and verification
- Session management with Express sessions

### MVC Backend Structure
Complete MVC architecture implemented in `/backend/` folder:
- **Controllers**: AuthController.js for authentication logic
- **Models**: User.js, Role.js, UserRegistrationData.js for data operations
- **Routes**: authRoutes.js for API endpoint definitions
- **Middleware**: authMiddleware.js, validationMiddleware.js for security
- **Config**: database.js for SQLite/MySQL database configuration

### Authentication System
- Username/password authentication with bcrypt hashing
- Role-based access control (admin, corporate, regional, branch, user)
- Session management with Express sessions
- JWT token support for stateless authentication
- Rate limiting and security middleware (helmet, cors)
- Input validation using express-validator

### Data Models
Key entities include:
- **Users**: Complete profile with username, email, phone, role assignment, verification status
- **Roles**: Hierarchical role system (admin=1, corporate=2, regional=3, branch=4, user=5)
- **UserRegistrationData**: Registration tracking with IP, user agent, UTM parameters
- **Sessions**: Secure session management with MySQL/SQLite storage

## Development & Build System

### Development Environment
- **Vite** development server with HMR and React Fast Refresh
- **TSX** for running TypeScript files directly in development
- Integrated error handling with runtime error overlays

### Build Process
- Client build generates optimized static assets in `dist/public`
- Server build uses **esbuild** for fast bundling with external dependencies
- Type checking with TypeScript compiler

### File Organization
```
project/
├── client/          # React frontend application
│   ├── src/
│   │   ├── components/  # Reusable UI components
│   │   ├── pages/       # Route components
│   │   ├── hooks/       # Custom React hooks
│   │   └── lib/         # Utilities and configurations
├── server/          # Express.js backend
├── shared/          # Shared types and schemas
└── migrations/      # Database migration files
```

# External Dependencies

## Database Services
- **Neon Database**: Serverless PostgreSQL hosting with connection pooling
- **PostgreSQL**: Primary database with advanced features like UUID generation

## UI & Design System
- **Radix UI**: Accessible primitive components for complex UI patterns
- **shadcn/ui**: Pre-built component library with customizable styling
- **Tailwind CSS**: Utility-first CSS framework with responsive design
- **Bootstrap**: Additional responsive utilities and grid system
- **Lucide React**: Consistent icon library

## Development Tools
- **Vite**: Fast build tool with plugin ecosystem
- **ESBuild**: Fast JavaScript bundler for production builds
- **TypeScript**: Type safety and enhanced developer experience
- **Drizzle Kit**: Database schema management and migration tool

## External APIs & Services
- **Google Fonts**: Web font delivery (Inter font family)
- **Bootstrap Icons**: Icon library for UI elements
- **Unsplash**: Stock photography for carousel images and placeholders

## Form & Validation
- **React Hook Form**: Performant form library with minimal re-renders
- **Zod**: TypeScript-first schema validation
- **@hookform/resolvers**: Integration between React Hook Form and Zod

## Query & State Management
- **TanStack Query**: Server state synchronization with caching and background updates
- Automatic retry logic and optimistic updates for better user experience