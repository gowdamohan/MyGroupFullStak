import { Switch, Route } from "wouter";
import { lazy, Suspense } from "react";
import { queryClient } from "./lib/queryClient";
import { QueryClientProvider } from "@tanstack/react-query";
import { Toaster } from "@/components/ui/toaster";
import { TooltipProvider } from "@/components/ui/tooltip";
import Home from "@/pages/home";
import LoginPage from "@/pages/login";
import AdminDashboard from "@/pages/dashboard/admin";
import CorporateDashboard from "@/pages/dashboard/corporate";
import RegionalDashboard from "@/pages/dashboard/regional";
import BranchDashboard from "@/pages/dashboard/branch";
import NotFound from "@/pages/not-found";

// Lazy load admin sub-pages
const AdminProfile = lazy(() => import("@/pages/dashboard/admin/profile"));
const AdminContent = lazy(() => import("@/pages/dashboard/admin/content"));
const AdminCategories = lazy(() => import("@/pages/dashboard/admin/categories"));
const AdminAds = lazy(() => import("@/pages/dashboard/admin/ads"));
const AdminCorporateLogin = lazy(() => import("@/pages/dashboard/admin/corporate-login"));

function Router() {
  return (
    <Switch>
      <Route path="/" component={Home} />
      <Route path="/auth/login" component={LoginPage} />
      <Route path="/dashboard/admin" component={AdminDashboard} />
      <Route path="/dashboard/admin/profile">
        <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
          <AdminProfile />
        </Suspense>
      </Route>
      <Route path="/dashboard/admin/content">
        <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
          <AdminContent />
        </Suspense>
      </Route>
      <Route path="/dashboard/admin/categories">
        <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
          <AdminCategories />
        </Suspense>
      </Route>
      <Route path="/dashboard/admin/ads">
        <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
          <AdminAds />
        </Suspense>
      </Route>
      <Route path="/dashboard/admin/corporate-login">
        <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
          <AdminCorporateLogin />
        </Suspense>
      </Route>
      <Route path="/dashboard/corporate" component={CorporateDashboard} />
      <Route path="/dashboard/regional" component={RegionalDashboard} />
      <Route path="/dashboard/branch" component={BranchDashboard} />
      <Route component={NotFound} />
    </Switch>
  );
}

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <TooltipProvider>
        <Toaster />
        <Router />
      </TooltipProvider>
    </QueryClientProvider>
  );
}

export default App;
