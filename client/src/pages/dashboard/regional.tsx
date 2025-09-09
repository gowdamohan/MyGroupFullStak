import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import StatsCard from "@/components/dashboard/StatsCard";
import ChartCard from "@/components/dashboard/ChartCard";
import { regionalMenuItems } from "@/config/regional_menu";

export default function RegionalDashboard() {
  const [selectedPeriod, setSelectedPeriod] = useState('30d');

  // Mock data queries - replace with real API calls
  const statsQuery = useQuery({
    queryKey: ['/api/regional/stats', selectedPeriod],
    queryFn: () => Promise.resolve({
      totalBranches: 42,
      activeBranches: 39,
      totalEmployees: 821,
      monthlyTargets: 85.6,
      regionRevenue: 224000,
      branchRequests: 3
    })
  });

  const chartData = useQuery({
    queryKey: ['/api/regional/charts', selectedPeriod],
    queryFn: () => Promise.resolve({
      branchPerformance: [
        { branch: 'Downtown', performance: 95, target: 90 },
        { branch: 'Mall Plaza', performance: 88, target: 85 },
        { branch: 'Airport', performance: 92, target: 88 },
        { branch: 'University', performance: 78, target: 80 },
        { branch: 'Industrial', performance: 96, target: 85 }
      ],
      weeklyMetrics: [
        { week: 'Week 1', revenue: 52000, customers: 1240 },
        { week: 'Week 2', revenue: 58000, customers: 1380 },
        { week: 'Week 3', revenue: 61000, customers: 1420 },
        { week: 'Week 4', revenue: 53000, customers: 1290 }
      ]
    })
  });

  return (
    <DashboardLayout
      title="Regional Dashboard - East Region"
      userRole="regional"
      menuItems={regionalMenuItems}
    >
      {/* Period Selector */}
      <div className="row mb-4">
        <div className="col-12">
          <div className="card">
            <div className="card-body d-flex justify-content-between align-items-center">
              <h5 className="card-title mb-0">Regional Overview - East Region</h5>
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
            title="Total Branches"
            value={statsQuery.data?.totalBranches?.toString() || '0'}
            icon="bi-building"
            color="primary"
            change="+3"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Active Branches"
            value={statsQuery.data?.activeBranches?.toString() || '0'}
            icon="bi-building-check"
            color="success"
            change="39/42"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Total Employees"
            value={statsQuery.data?.totalEmployees?.toString() || '0'}
            icon="bi-people"
            color="info"
            change="+28"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Monthly Revenue"
            value={`$${statsQuery.data?.regionRevenue?.toLocaleString() || '0'}`}
            icon="bi-currency-dollar"
            color="warning"
            change="+8.4%"
            isLoading={statsQuery.isLoading}
          />
        </div>
      </div>

      {/* Target Achievement */}
      <div className="row g-4 mb-4">
        <div className="col-lg-4">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Monthly Target Achievement</h5>
            </div>
            <div className="card-body text-center">
              <div className="position-relative d-inline-block mb-3">
                <svg width="120" height="120" className="transform-rotate-90">
                  <circle
                    cx="60"
                    cy="60"
                    r="50"
                    fill="none"
                    stroke="#e9ecef"
                    strokeWidth="8"
                  />
                  <circle
                    cx="60"
                    cy="60"
                    r="50"
                    fill="none"
                    stroke="#17a2b8"
                    strokeWidth="8"
                    strokeDasharray={`${2 * Math.PI * 50 * 0.856} ${2 * Math.PI * 50}`}
                    strokeLinecap="round"
                  />
                </svg>
                <div className="position-absolute top-50 start-50 translate-middle">
                  <h3 className="mb-0">85.6%</h3>
                  <small className="text-muted">Target</small>
                </div>
              </div>
              <p className="text-muted mb-0">Monthly target achievement across all branches</p>
            </div>
          </div>
        </div>
        
        <div className="col-lg-8">
          <ChartCard
            title="Branch Performance vs Targets"
            data={chartData.data?.branchPerformance || []}
            type="bar"
            isLoading={chartData.isLoading}
          />
        </div>
      </div>

      {/* Branch Status & Recent Activities */}
      <div className="row g-4">
        <div className="col-lg-8">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Branch Status Overview</h5>
            </div>
            <div className="card-body">
              <div className="table-responsive">
                <table className="table table-hover">
                  <thead>
                    <tr>
                      <th>Branch</th>
                      <th>Manager</th>
                      <th>Staff</th>
                      <th>This Month</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><i className="bi bi-building me-2"></i>Downtown</td>
                      <td>Sarah Johnson</td>
                      <td>24</td>
                      <td>$52,000</td>
                      <td><span className="badge bg-success">Excellent</span></td>
                      <td><button className="btn btn-sm btn-outline-primary">View</button></td>
                    </tr>
                    <tr>
                      <td><i className="bi bi-building me-2"></i>Mall Plaza</td>
                      <td>Mike Chen</td>
                      <td>18</td>
                      <td>$41,000</td>
                      <td><span className="badge bg-warning">Below Target</span></td>
                      <td><button className="btn btn-sm btn-outline-warning">Support</button></td>
                    </tr>
                    <tr>
                      <td><i className="bi bi-building me-2"></i>Airport</td>
                      <td>Emma Davis</td>
                      <td>22</td>
                      <td>$48,000</td>
                      <td><span className="badge bg-success">Good</span></td>
                      <td><button className="btn btn-sm btn-outline-primary">View</button></td>
                    </tr>
                    <tr>
                      <td><i className="bi bi-building me-2"></i>University</td>
                      <td>Alex Rodriguez</td>
                      <td>15</td>
                      <td>$32,000</td>
                      <td><span className="badge bg-danger">Needs Attention</span></td>
                      <td><button className="btn btn-sm btn-outline-danger">Urgent</button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        
        <div className="col-lg-4">
          <div className="card">
            <div className="card-header d-flex justify-content-between align-items-center">
              <h5 className="card-title mb-0">Recent Activities</h5>
              <span className="badge bg-info">5 New</span>
            </div>
            <div className="card-body">
              <div className="d-flex align-items-center mb-3">
                <div className="bg-success rounded-circle p-2 me-3">
                  <i className="bi bi-check-circle text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Monthly targets achieved</h6>
                  <small className="text-muted">Downtown Branch - 95%</small>
                </div>
                <small className="text-muted">2h</small>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-warning rounded-circle p-2 me-3">
                  <i className="bi bi-exclamation-triangle text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Staff shortage alert</h6>
                  <small className="text-muted">University Branch - 3 vacant</small>
                </div>
                <small className="text-muted">4h</small>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-info rounded-circle p-2 me-3">
                  <i className="bi bi-person-plus text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">New employee onboarded</h6>
                  <small className="text-muted">Airport Branch - John Smith</small>
                </div>
                <small className="text-muted">1d</small>
              </div>
              <button className="btn btn-outline-primary btn-sm w-100">
                <i className="bi bi-eye me-2"></i>View All Activities
              </button>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}