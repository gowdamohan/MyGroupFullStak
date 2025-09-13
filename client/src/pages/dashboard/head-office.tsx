import React, { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import StatsCard from "@/components/dashboard/StatsCard";
import ChartCard from "@/components/dashboard/ChartCard";

export default function HeadOfficeDashboard() {
  const [selectedPeriod, setSelectedPeriod] = useState('30d');

  // Mock data queries - replace with real API calls
  const statsQuery = useQuery({
    queryKey: ['/api/head-office/stats', selectedPeriod],
    queryFn: () => Promise.resolve({
      totalCorporates: 5,
      totalRegions: 48,
      totalBranches: 892,
      totalEmployees: 15420,
      overallRevenue: 4250000,
      complianceScore: 96.8,
      strategicInitiatives: 12,
      boardMeetings: 3
    })
  });

  const chartData = useQuery({
    queryKey: ['/api/head-office/charts', selectedPeriod],
    queryFn: () => Promise.resolve({
      corporatePerformance: [
        { corporate: 'Corp A', revenue: 1200000, performance: 94 },
        { corporate: 'Corp B', revenue: 980000, performance: 91 },
        { corporate: 'Corp C', revenue: 1100000, performance: 96 },
        { corporate: 'Corp D', revenue: 750000, performance: 88 },
        { corporate: 'Corp E', revenue: 920000, performance: 92 }
      ],
      quarterlyTrends: [
        { quarter: 'Q1', revenue: 3800000, growth: 8.5 },
        { quarter: 'Q2', revenue: 4100000, growth: 12.2 },
        { quarter: 'Q3', revenue: 4250000, growth: 15.8 },
        { quarter: 'Q4', revenue: 4400000, growth: 18.3 }
      ]
    })
  });

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/head-office', active: true },
    { icon: 'bi-building-gear', label: 'Corporate Oversight', path: '/dashboard/head-office/corporates' },
    { icon: 'bi-graph-up-arrow', label: 'Strategic Planning', path: '/dashboard/head-office/strategy' },
    { icon: 'bi-shield-check', label: 'Compliance & Risk', path: '/dashboard/head-office/compliance' },
    { icon: 'bi-people-fill', label: 'Board Relations', path: '/dashboard/head-office/board' },
    { icon: 'bi-bar-chart', label: 'Executive Reports', path: '/dashboard/head-office/reports' },
    { icon: 'bi-currency-exchange', label: 'Financial Overview', path: '/dashboard/head-office/finance' },
    { icon: 'bi-globe', label: 'Market Analysis', path: '/dashboard/head-office/market' },
    { icon: 'bi-gear-wide-connected', label: 'System Integration', path: '/dashboard/head-office/integration' },
  ];

  return (
    <DashboardLayout 
      title="Head Office Dashboard" 
      userRole="head_office"
      menuItems={menuItems}
    >
      {/* Period Selector */}
      <div className="row mb-4">
        <div className="col-12">
          <div className="card">
            <div className="card-body d-flex justify-content-between align-items-center">
              <h5 className="card-title mb-0">Executive Overview</h5>
              <div className="d-flex gap-2">
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
                <button className="btn btn-outline-primary">
                  <i className="bi bi-download me-2"></i>Export Report
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Executive Stats Cards */}
      <div className="row g-4 mb-4">
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Total Corporates"
            value={statsQuery.data?.totalCorporates?.toString() || '0'}
            icon="bi-building-gear"
            color="primary"
            change="+1"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Total Regions"
            value={statsQuery.data?.totalRegions?.toString() || '0'}
            icon="bi-diagram-3"
            color="success"
            change="+8"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Total Branches"
            value={statsQuery.data?.totalBranches?.toLocaleString() || '0'}
            icon="bi-building"
            color="info"
            change="+45"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Overall Revenue"
            value={`$${(statsQuery.data?.overallRevenue || 0).toLocaleString()}`}
            icon="bi-currency-dollar"
            color="warning"
            change="+18.3%"
            isLoading={statsQuery.isLoading}
          />
        </div>
      </div>

      {/* Secondary Stats */}
      <div className="row g-4 mb-4">
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <div className="bg-success rounded-circle p-3 mx-auto mb-3" style={{ width: '60px', height: '60px' }}>
                <i className="bi bi-people text-white fs-4"></i>
              </div>
              <h4 className="mb-1">{statsQuery.data?.totalEmployees?.toLocaleString() || '0'}</h4>
              <p className="text-muted mb-0">Total Employees</p>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <div className="bg-info rounded-circle p-3 mx-auto mb-3" style={{ width: '60px', height: '60px' }}>
                <i className="bi bi-shield-check text-white fs-4"></i>
              </div>
              <h4 className="mb-1">{statsQuery.data?.complianceScore || 0}%</h4>
              <p className="text-muted mb-0">Compliance Score</p>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <div className="bg-warning rounded-circle p-3 mx-auto mb-3" style={{ width: '60px', height: '60px' }}>
                <i className="bi bi-lightbulb text-white fs-4"></i>
              </div>
              <h4 className="mb-1">{statsQuery.data?.strategicInitiatives || 0}</h4>
              <p className="text-muted mb-0">Strategic Initiatives</p>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <div className="bg-danger rounded-circle p-3 mx-auto mb-3" style={{ width: '60px', height: '60px' }}>
                <i className="bi bi-calendar-event text-white fs-4"></i>
              </div>
              <h4 className="mb-1">{statsQuery.data?.boardMeetings || 0}</h4>
              <p className="text-muted mb-0">Board Meetings</p>
            </div>
          </div>
        </div>
      </div>

      {/* Charts */}
      <div className="row g-4 mb-4">
        <div className="col-lg-8">
          <ChartCard
            title="Corporate Performance Overview"
            data={chartData.data?.corporatePerformance || []}
            type="bar"
            isLoading={chartData.isLoading}
          />
        </div>
        <div className="col-lg-4">
          <ChartCard
            title="Quarterly Growth Trends"
            data={chartData.data?.quarterlyTrends || []}
            type="line"
            isLoading={chartData.isLoading}
          />
        </div>
      </div>

      {/* Executive Summary & Strategic Initiatives */}
      <div className="row g-4">
        <div className="col-lg-8">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Corporate Performance Summary</h5>
            </div>
            <div className="card-body">
              <div className="table-responsive">
                <table className="table table-hover">
                  <thead>
                    <tr>
                      <th>Corporate</th>
                      <th>Regions</th>
                      <th>Branches</th>
                      <th>Employees</th>
                      <th>Revenue</th>
                      <th>Performance</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><strong>Corporate A</strong></td>
                      <td>12</td>
                      <td>245</td>
                      <td>4,250</td>
                      <td>$1,200,000</td>
                      <td><span className="badge bg-success">94%</span></td>
                      <td><span className="badge bg-success">Excellent</span></td>
                    </tr>
                    <tr>
                      <td><strong>Corporate B</strong></td>
                      <td>8</td>
                      <td>156</td>
                      <td>2,890</td>
                      <td>$980,000</td>
                      <td><span className="badge bg-success">91%</span></td>
                      <td><span className="badge bg-success">Good</span></td>
                    </tr>
                    <tr>
                      <td><strong>Corporate C</strong></td>
                      <td>15</td>
                      <td>298</td>
                      <td>5,120</td>
                      <td>$1,100,000</td>
                      <td><span className="badge bg-success">96%</span></td>
                      <td><span className="badge bg-success">Excellent</span></td>
                    </tr>
                    <tr>
                      <td><strong>Corporate D</strong></td>
                      <td>6</td>
                      <td>98</td>
                      <td>1,680</td>
                      <td>$750,000</td>
                      <td><span className="badge bg-warning">88%</span></td>
                      <td><span className="badge bg-warning">Needs Attention</span></td>
                    </tr>
                    <tr>
                      <td><strong>Corporate E</strong></td>
                      <td>7</td>
                      <td>95</td>
                      <td>1,480</td>
                      <td>$920,000</td>
                      <td><span className="badge bg-success">92%</span></td>
                      <td><span className="badge bg-success">Good</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        
        <div className="col-lg-4">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Strategic Initiatives</h5>
            </div>
            <div className="card-body">
              <div className="d-flex align-items-center mb-3">
                <div className="bg-primary rounded-circle p-2 me-3">
                  <i className="bi bi-globe text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Global Expansion</h6>
                  <small className="text-muted">Q4 2024 - 85% Complete</small>
                  <div className="progress mt-1" style={{ height: '4px' }}>
                    <div className="progress-bar bg-primary" style={{ width: '85%' }}></div>
                  </div>
                </div>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-success rounded-circle p-2 me-3">
                  <i className="bi bi-cpu text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Digital Transformation</h6>
                  <small className="text-muted">Q1 2025 - 65% Complete</small>
                  <div className="progress mt-1" style={{ height: '4px' }}>
                    <div className="progress-bar bg-success" style={{ width: '65%' }}></div>
                  </div>
                </div>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-info rounded-circle p-2 me-3">
                  <i className="bi bi-leaf text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Sustainability Program</h6>
                  <small className="text-muted">Q2 2025 - 40% Complete</small>
                  <div className="progress mt-1" style={{ height: '4px' }}>
                    <div className="progress-bar bg-info" style={{ width: '40%' }}></div>
                  </div>
                </div>
              </div>
              <button className="btn btn-primary btn-sm w-100">
                <i className="bi bi-eye me-2"></i>View All Initiatives
              </button>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
