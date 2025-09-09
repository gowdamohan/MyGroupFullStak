import { Switch, Route } from "wouter";
import { lazy, Suspense } from "react";
import { queryClient } from "./lib/queryClient";
import { QueryClientProvider } from "@tanstack/react-query";
import { Toaster } from "@/components/ui/toaster";
import { TooltipProvider } from "@/components/ui/tooltip";
import { AuthProvider } from "@/hooks/use-auth";
import { AdminRoute } from "@/components/ProtectedRoute";
import Home from "@/pages/home";
import LoginPage from "@/pages/login";
import AdminLoginPage from "@/pages/admin-login";
import AdminDashboardPage from "@/pages/admin-dashboard";
import AdminDashboard from "@/pages/dashboard/admin";
import CorporateDashboard from "@/pages/dashboard/corporate";
import RegionalDashboard from "@/pages/dashboard/regional";
import BranchDashboard from "@/pages/dashboard/branch";
import HeadOfficeDashboard from "@/pages/dashboard/head-office";
import Logout from "@/pages/logout";
import NotFound from "@/pages/not-found";

// Lazy load admin sub-pages
const AdminCategories = lazy(() => import("@/pages/dashboard/admin/categories"));
const AdminSettings = lazy(() => import("@/pages/dashboard/admin/admin-settings"));
const AdminCorporateLogin = lazy(() => import("@/pages/dashboard/admin/corporate-login"));

// Lazy load admin profile components
const ProfileGroup = lazy(() => import("@/pages/dashboard/admin/profile/group"));
const ProfileAppCreated = lazy(() => import("@/pages/dashboard/admin/profile/app-created"));
const ProfileChangePassword = lazy(() => import("@/pages/dashboard/admin/profile/change-password"));

// Lazy load admin location components
const LocationContent = lazy(() => import("@/pages/dashboard/admin/location/content"));
const LocationCountry = lazy(() => import("@/pages/dashboard/admin/location/country"));
const LocationState = lazy(() => import("@/pages/dashboard/admin/location/state"));
const LocationDistrict = lazy(() => import("@/pages/dashboard/admin/location/district"));

// Lazy load admin management components
const LanguageManagement = lazy(() => import("@/pages/dashboard/admin/language"));
const EducationManagement = lazy(() => import("@/pages/dashboard/admin/education"));
const ProfessionManagement = lazy(() => import("@/pages/dashboard/admin/profession"));

function Router() {
  return (
    <Switch>
      <Route path="/" component={Home} />
      <Route path="/auth/login" component={LoginPage} />
      <Route path="/admin/login" component={AdminLoginPage} />
      <Route path="/admin/dashboard">
        <AdminRoute>
          <AdminDashboardPage />
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin">
        <AdminRoute>
          <AdminDashboard />
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/categories">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <AdminCategories />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/admin-settings">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <AdminSettings />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/corporate-login">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <AdminCorporateLogin />
          </Suspense>
        </AdminRoute>
      </Route>

      {/* Profile Routes */}
      <Route path="/dashboard/admin/profile/group">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <ProfileGroup />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/profile/app-created">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <ProfileAppCreated />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/profile/change-password">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <ProfileChangePassword />
          </Suspense>
        </AdminRoute>
      </Route>

      {/* Location Routes */}
      <Route path="/dashboard/admin/location/content">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <LocationContent />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/location/country">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <LocationCountry />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/location/state">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <LocationState />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/location/district">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <LocationDistrict />
          </Suspense>
        </AdminRoute>
      </Route>

      {/* Management Routes */}
      <Route path="/dashboard/admin/language">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <LanguageManagement />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/education">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <EducationManagement />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/admin/profession">
        <AdminRoute>
          <Suspense fallback={<div className="text-center p-5"><div className="spinner-border"></div></div>}>
            <ProfessionManagement />
          </Suspense>
        </AdminRoute>
      </Route>
      <Route path="/dashboard/corporate" component={CorporateDashboard} />
      <Route path="/dashboard/regional" component={RegionalDashboard} />
      <Route path="/dashboard/branch" component={BranchDashboard} />
      <Route path="/dashboard/head-office" component={HeadOfficeDashboard} />
      <Route path="/logout" component={Logout} />
      <Route component={NotFound} />
    </Switch>
  );
}

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <TooltipProvider>
          <Toaster />
          <Router />
        </TooltipProvider>
      </AuthProvider>
    </QueryClientProvider>
  );
}

export default App;
