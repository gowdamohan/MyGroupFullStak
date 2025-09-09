import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import StatsCard from "@/components/dashboard/StatsCard";
import ChartCard from "@/components/dashboard/ChartCard";
import { branchMenuItems } from "@/config/branch_menu";

export default function BranchDashboard() {
  const [selectedPeriod, setSelectedPeriod] = useState('30d');

  // Mock data queries - replace with real API calls
  const statsQuery = useQuery({
    queryKey: ['/api/branch/stats', selectedPeriod],
    queryFn: () => Promise.resolve({
      totalStaff: 24,
      activeStaff: 22,
      dailyCustomers: 89,
      monthlyRevenue: 52000,
      customerSatisfaction: 4.7,
      tasksCompleted: 18
    })
  });

  const chartData = useQuery({
    queryKey: ['/api/branch/charts', selectedPeriod],
    queryFn: () => Promise.resolve({
      dailyMetrics: [
        { day: 'Mon', customers: 78, revenue: 1850 },
        { day: 'Tue', customers: 92, revenue: 2100 },
        { day: 'Wed', customers: 85, revenue: 1950 },
        { day: 'Thu', customers: 96, revenue: 2200 },
        { day: 'Fri', customers: 104, revenue: 2400 },
        { day: 'Sat', customers: 89, revenue: 2050 },
        { day: 'Sun', customers: 67, revenue: 1600 }
      ],
      staffPerformance: [
        { name: 'John Smith', tasks: 45, rating: 4.8 },
        { name: 'Alice Johnson', tasks: 42, rating: 4.6 },
        { name: 'Bob Wilson', tasks: 38, rating: 4.5 },
        { name: 'Carol Brown', tasks: 47, rating: 4.9 }
      ]
    })
  });

  return (
    <DashboardLayout
      title="Branch Dashboard - Downtown Branch"
      userRole="branch"
      menuItems={branchMenuItems}
    >
      {/* Period Selector */}
      <div className="row mb-4">
        <div className="col-12">
          <div className="card">
            <div className="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 className="card-title mb-0">Downtown Branch Operations</h5>
                <small className="text-muted">Manager: Sarah Johnson | East Region</small>
              </div>
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
            title="Total Staff"
            value={statsQuery.data?.totalStaff?.toString() || '0'}
            icon="bi-people"
            color="primary"
            change="+2"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Active Today"
            value={statsQuery.data?.activeStaff?.toString() || '0'}
            icon="bi-person-check"
            color="success"
            change="22/24"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Daily Customers"
            value={statsQuery.data?.dailyCustomers?.toString() || '0'}
            icon="bi-person-hearts"
            color="info"
            change="+15%"
            isLoading={statsQuery.isLoading}
          />
        </div>
        <div className="col-md-6 col-xl-3">
          <StatsCard
            title="Monthly Revenue"
            value={`$${statsQuery.data?.monthlyRevenue?.toLocaleString() || '0'}`}
            icon="bi-currency-dollar"
            color="warning"
            change="+12.3%"
            isLoading={statsQuery.isLoading}
          />
        </div>
      </div>

      {/* Customer Satisfaction & Daily Performance */}
      <div className="row g-4 mb-4">
        <div className="col-lg-4">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Customer Satisfaction</h5>
            </div>
            <div className="card-body text-center">
              <div className="mb-3">
                <h2 className="text-warning mb-0">
                  <i className="bi bi-star-fill"></i> 4.7
                </h2>
                <div className="text-warning">
                  <i className="bi bi-star-fill"></i>
                  <i className="bi bi-star-fill"></i>
                  <i className="bi bi-star-fill"></i>
                  <i className="bi bi-star-fill"></i>
                  <i className="bi bi-star-half"></i>
                </div>
              </div>
              <p className="text-muted mb-0">Based on 143 reviews this month</p>
              <small className="text-success">+0.3 from last month</small>
            </div>
          </div>
        </div>
        
        <div className="col-lg-8">
          <ChartCard
            title="Daily Performance (This Week)"
            data={chartData.data?.dailyMetrics || []}
            type="line"
            isLoading={chartData.isLoading}
          />
        </div>
      </div>

      {/* Staff Performance & Today's Tasks */}
      <div className="row g-4">
        <div className="col-lg-8">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Staff Performance Today</h5>
            </div>
            <div className="card-body">
              <div className="table-responsive">
                <table className="table table-hover">
                  <thead>
                    <tr>
                      <th>Staff Member</th>
                      <th>Position</th>
                      <th>Tasks Completed</th>
                      <th>Customer Rating</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div className="d-flex align-items-center">
                          <div className="bg-primary rounded-circle p-2 me-2">
                            <i className="bi bi-person text-white"></i>
                          </div>
                          John Smith
                        </div>
                      </td>
                      <td>Senior Associate</td>
                      <td>8/10</td>
                      <td>
                        <span className="text-warning">
                          <i className="bi bi-star-fill"></i> 4.8
                        </span>
                      </td>
                      <td><span className="badge bg-success">Active</span></td>
                      <td><button className="btn btn-sm btn-outline-primary">View</button></td>
                    </tr>
                    <tr>
                      <td>
                        <div className="d-flex align-items-center">
                          <div className="bg-success rounded-circle p-2 me-2">
                            <i className="bi bi-person text-white"></i>
                          </div>
                          Alice Johnson
                        </div>
                      </td>
                      <td>Customer Service</td>
                      <td>7/8</td>
                      <td>
                        <span className="text-warning">
                          <i className="bi bi-star-fill"></i> 4.6
                        </span>
                      </td>
                      <td><span className="badge bg-success">Active</span></td>
                      <td><button className="btn btn-sm btn-outline-primary">View</button></td>
                    </tr>
                    <tr>
                      <td>
                        <div className="d-flex align-items-center">
                          <div className="bg-warning rounded-circle p-2 me-2">
                            <i className="bi bi-person text-white"></i>
                          </div>
                          Bob Wilson
                        </div>
                      </td>
                      <td>Sales Associate</td>
                      <td>5/8</td>
                      <td>
                        <span className="text-warning">
                          <i className="bi bi-star-fill"></i> 4.5
                        </span>
                      </td>
                      <td><span className="badge bg-warning">Break</span></td>
                      <td><button className="btn btn-sm btn-outline-warning">Contact</button></td>
                    </tr>
                    <tr>
                      <td>
                        <div className="d-flex align-items-center">
                          <div className="bg-info rounded-circle p-2 me-2">
                            <i className="bi bi-person text-white"></i>
                          </div>
                          Carol Brown
                        </div>
                      </td>
                      <td>Team Lead</td>
                      <td>9/10</td>
                      <td>
                        <span className="text-warning">
                          <i className="bi bi-star-fill"></i> 4.9
                        </span>
                      </td>
                      <td><span className="badge bg-success">Active</span></td>
                      <td><button className="btn btn-sm btn-outline-primary">View</button></td>
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
              <h5 className="card-title mb-0">Today's Tasks</h5>
              <span className="badge bg-info">{statsQuery.data?.tasksCompleted || 0}/25</span>
            </div>
            <div className="card-body">
              <div className="d-flex align-items-center mb-3">
                <div className="bg-success rounded-circle p-2 me-3">
                  <i className="bi bi-check-circle text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Morning team meeting</h6>
                  <small className="text-muted">Completed at 9:00 AM</small>
                </div>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-success rounded-circle p-2 me-3">
                  <i className="bi bi-check-circle text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Inventory check</h6>
                  <small className="text-muted">Completed at 11:30 AM</small>
                </div>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-warning rounded-circle p-2 me-3">
                  <i className="bi bi-clock text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Customer feedback review</h6>
                  <small className="text-muted">Due at 3:00 PM</small>
                </div>
              </div>
              <div className="d-flex align-items-center mb-3">
                <div className="bg-secondary rounded-circle p-2 me-3">
                  <i className="bi bi-circle text-white"></i>
                </div>
                <div className="flex-grow-1">
                  <h6 className="mb-1">Daily sales report</h6>
                  <small className="text-muted">Due at 6:00 PM</small>
                </div>
              </div>
              <button className="btn btn-primary btn-sm w-100">
                <i className="bi bi-plus-circle me-2"></i>Add New Task
              </button>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}