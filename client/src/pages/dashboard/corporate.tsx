import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import StatsCard from "@/components/dashboard/StatsCard";
import ChartCard from "@/components/dashboard/ChartCard";
import { corporateMenuItems } from "@/config/corporate_menu";

export default function CorporateDashboard() {
  const [selectedPeriod, setSelectedPeriod] = useState('30d');

  // Mock data queries - replace with real API calls
  const statsQuery = useQuery({
    queryKey: ['/api/corporate/stats', selectedPeriod],
    queryFn: () => Promise.resolve({
      totalRegions: 12,
      activeBranches: 156,
      totalEmployees: 3245,
      monthlyRevenue: 892450,
      corporatePerformance: 94.2,
      pendingApprovals: 8
    })
  });

  const chartData = useQuery({
    queryKey: ['/api/corporate/charts', selectedPeriod],
    queryFn: () => Promise.resolve({
      regionPerformance: [
        { region: 'North', performance: 95 },
        { region: 'South', performance: 88 },
        { region: 'East', performance: 92 },
        { region: 'West', performance: 89 },
        { region: 'Central', performance: 96 }
      ],
      monthlyMetrics: [
        { month: 'Jan', revenue: 750000, employees: 3100 },
        { month: 'Feb', revenue: 820000, employees: 3150 },
        { month: 'Mar', revenue: 890000, employees: 3200 },
        { month: 'Apr', revenue: 920000, employees: 3245 }
      ]
    })
  });



  return (
    <DashboardLayout
      title="Corporate Dashboard"
      userRole="corporate"
      menuItems={corporateMenuItems}
    >
      {/* Period Selector */}
      <div className="row mb-4">
        <div className="col-12">
          <div className="card">
            <div className="card-body d-flex justify-content-between align-items-center">
              <h5 className="card-title mb-0">Corporate Overview</h5>
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
            title="Total Regions"
            value={statsQuery.data?.totalRegions?.toString() || '0'}
            icon="bi-diagram-3"
            color="primary"
            change="+2"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Active Branches"
            value={statsQuery.data?.activeBranches?.toString() || '0'}
            icon="bi-building"
            color="success"
            change="+15"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Total Employees"
            value={statsQuery.data?.totalEmployees?.toLocaleString() || '0'}
            icon="bi-people"
            color="info"
            change="+145"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Monthly Revenue"
            value={`$${statsQuery.data?.monthlyRevenue?.toLocaleString() || '0'}`}
            icon="bi-currency-dollar"
            color="warning"
            change="+12.8%"
            isLoading={statsQuery.isLoading}
          />
        </div>
      </div>

      {/* Charts */}
      <div className="row g-4 mb-4">
        <div className="col-lg-8">
          <ChartCard
            title="Regional Performance"
            data={chartData.data?.regionPerformance || []}
            type="bar"
            isLoading={chartData.isLoading}
          />
        </div>
        <div className="col-lg-4">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Performance Score</h5>
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
                    stroke="#28a745"
                    strokeWidth="8"
                    strokeDasharray={`${2 * Math.PI * 50 * 0.942} ${2 * Math.PI * 50}`}
                    strokeLinecap="round"
                  />
                </svg>
                <div className="position-absolute top-50 start-50 translate-middle">
                  <h3 className="mb-0">94.2%</h3>
                  <small className="text-muted">Performance</small>
                </div>
              </div>
              <p className="text-muted mb-0">Corporate performance index based on all regions</p>
            </div>
          </div>
        </div>
      </div>

      {/* Regional Summary & Pending Actions */}
      <div className="row g-4">
        <div className="col-lg-8">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Regional Summary</h5>
            </div>
            <div className="card-body">
              <div className="table-responsive">
                <table className="table table-hover">
                  <thead>
                    <tr>
                      <th>Region</th>
                      <th>Branches</th>
                      <th>Employees</th>
                      <th>Revenue</th>
                      <th>Performance</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><i className="bi bi-geo me-2"></i>North Region</td>
                      <td>45</td>
                      <td>892</td>
                      <td>$245,000</td>
                      <td><span className="badge bg-success">95%</span></td>
                    </tr>
                    <tr>
                      <td><i className="bi bi-geo me-2"></i>South Region</td>
                      <td>38</td>
                      <td>743</td>
                      <td>$198,000</td>
                      <td><span className="badge bg-warning">88%</span></td>
                    </tr>
                    <tr>
                      <td><i className="bi bi-geo me-2"></i>East Region</td>
                      <td>42</td>
                      <td>821</td>
                      <td>$224,000</td>
                      <td><span className="badge bg-success">92%</span></td>
                    </tr>
                    <tr>
                      <td><i className="bi bi-geo me-2"></i>West Region</td>
                      <td>31</td>
                      <td>589</td>
                      <td>$175,000</td>
                      <td><span className="badge bg-warning">89%</span></td>
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
              <h5 className="card-title mb-0">Pending Approvals</h5>
              <span className="badge bg-warning">{statsQuery.data?.pendingApprovals || 0}</span>
            </div>
            <div className="card-body">
              <div className="d-flex align-items-center mb-3">
                <div className="bg-info rounded-circle p-2 me-3">
                  <i className="bi bi-person-plus text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">New Branch Manager</h6>
                  <small className="text-muted">West Region - Seattle</small>
                </div>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-warning rounded-circle p-2 me-3">
                  <i className="bi bi-currency-dollar text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Budget Request</h6>
                  <small className="text-muted">North Region - $50,000</small>
                </div>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-success rounded-circle p-2 me-3">
                  <i className="bi bi-building text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">New Branch Opening</h6>
                  <small className="text-muted">East Region - Boston</small>
                </div>
              </div>
              <button className="btn btn-primary btn-sm w-100">
                <i className="bi bi-eye me-2"></i>View All Approvals
              </button>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}