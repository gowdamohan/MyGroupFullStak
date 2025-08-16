import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import StatsCard from "@/components/dashboard/StatsCard";
import ChartCard from "@/components/dashboard/ChartCard";

export default function AdminDashboard() {
  const [selectedPeriod, setSelectedPeriod] = useState('30d');

  // Mock data queries - replace with real API calls
  const statsQuery = useQuery({
    queryKey: ['/api/admin/stats', selectedPeriod],
    queryFn: () => Promise.resolve({
      totalUsers: 12567,
      activeUsers: 8934,
      totalRevenue: 145890,
      newSignups: 423,
      systemHealth: 98.5,
      supportTickets: 23
    })
  });

  const chartData = useQuery({
    queryKey: ['/api/admin/charts', selectedPeriod],
    queryFn: () => Promise.resolve({
      userGrowth: [
        { month: 'Jan', users: 1200 },
        { month: 'Feb', users: 1800 },
        { month: 'Mar', users: 2400 },
        { month: 'Apr', users: 3200 },
        { month: 'May', users: 4100 },
        { month: 'Jun', users: 4800 }
      ],
      revenue: [
        { month: 'Jan', amount: 12000 },
        { month: 'Feb', amount: 18000 },
        { month: 'Mar', amount: 24000 },
        { month: 'Apr', amount: 32000 },
        { month: 'May', amount: 41000 },
        { month: 'Jun', amount: 48000 }
      ]
    })
  });

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin', active: true },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content', path: '/dashboard/admin/content' },
    { icon: 'bi-tags', label: 'Categories' }, // No path - will be dropdown
    { icon: 'bi-megaphone', label: 'My Ads', path: '/dashboard/admin/ads' },
    { icon: 'bi-building', label: 'Corporate Login', path: '/dashboard/admin/corporate-login' },
    { icon: 'bi-gear', label: 'System Settings', path: '/dashboard/admin/settings' },
  ];

  return (
    <DashboardLayout 
      title="Admin Dashboard" 
      userRole="admin"
      menuItems={menuItems}
    >
      {/* Period Selector */}
      <div className="row mb-4">
        <div className="col-12">
          <div className="card">
            <div className="card-body d-flex justify-content-between align-items-center">
              <h5 className="card-title mb-0">System Overview</h5>
              <select 
                className="form-select w-auto"
                value={selectedPeriod}
                onChange={(e) => setSelectedPeriod(e.target.value)}
                data-testid="select-period"
              >
                <option value="7d">Last 7 days</option>
                <option value="30d">Last 30 days</option>
                <option value="90d">Last 90 days</option>
                <option value="1y">Last year</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="row g-4 mb-4">
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Total Users"
            value={statsQuery.data?.totalUsers?.toLocaleString() || '0'}
            icon="bi-people"
            color="primary"
            change="+12.5%"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Active Users"
            value={statsQuery.data?.activeUsers?.toLocaleString() || '0'}
            icon="bi-person-check"
            color="success"
            change="+8.2%"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Total Revenue"
            value={`$${statsQuery.data?.totalRevenue?.toLocaleString() || '0'}`}
            icon="bi-currency-dollar"
            color="info"
            change="+23.1%"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="System Health"
            value={`${statsQuery.data?.systemHealth || 0}%`}
            icon="bi-heart-pulse"
            color="warning"
            change="+0.5%"
            isLoading={statsQuery.isLoading}
          />
        </div>
      </div>

      {/* Charts */}
      <div className="row g-4 mb-4">
        <div className="col-lg-8">
          <ChartCard
            title="User Growth"
            data={chartData.data?.userGrowth || []}
            type="line"
            isLoading={chartData.isLoading}
          />
        </div>
        <div className="col-lg-4">
          <ChartCard
            title="Revenue Trend"
            data={chartData.data?.revenue || []}
            type="bar"
            isLoading={chartData.isLoading}
          />
        </div>
      </div>

      {/* Recent Activity & System Status */}
      <div className="row g-4">
        <div className="col-lg-8">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Recent System Activity</h5>
            </div>
            <div className="card-body">
              <div className="d-flex align-items-center mb-3">
                <div className="bg-primary rounded-circle p-2 me-3">
                  <i className="bi bi-person-plus text-white"></i>
                </div>
                <div>
                  <h6 className="mb-1">New user registration spike</h6>
                  <small className="text-muted">423 new users in the last 24 hours</small>
                </div>
                <small className="text-muted ms-auto">2 hours ago</small>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-success rounded-circle p-2 me-3">
                  <i className="bi bi-shield-check text-white"></i>
                </div>
                <div>
                  <h6 className="mb-1">Security update completed</h6>
                  <small className="text-muted">All systems updated successfully</small>
                </div>
                <small className="text-muted ms-auto">4 hours ago</small>
              </div>
              <div className="d-flex align-items-center">
                <div className="bg-warning rounded-circle p-2 me-3">
                  <i className="bi bi-exclamation-triangle text-white"></i>
                </div>
                <div>
                  <h6 className="mb-1">High server load detected</h6>
                  <small className="text-muted">Server CPU usage peaked at 85%</small>
                </div>
                <small className="text-muted ms-auto">6 hours ago</small>
              </div>
            </div>
          </div>
        </div>
        
        <div className="col-lg-4">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">System Status</h5>
            </div>
            <div className="card-body">
              <div className="d-flex justify-content-between align-items-center mb-3">
                <span>API Server</span>
                <span className="badge bg-success">Online</span>
              </div>
              <div className="d-flex justify-content-between align-items-center mb-3">
                <span>Database</span>
                <span className="badge bg-success">Online</span>
              </div>
              <div className="d-flex justify-content-between align-items-center mb-3">
                <span>CDN</span>
                <span className="badge bg-warning">Degraded</span>
              </div>
              <div className="d-flex justify-content-between align-items-center mb-3">
                <span>Email Service</span>
                <span className="badge bg-success">Online</span>
              </div>
              <div className="d-flex justify-content-between align-items-center">
                <span>Support Tickets</span>
                <span className="badge bg-info">{statsQuery.data?.supportTickets || 0} Open</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}