interface StatsCardProps {
  title: string;
  value: string;
  icon: string;
  color: 'primary' | 'success' | 'info' | 'warning' | 'danger' | 'secondary';
  change?: string;
  isLoading?: boolean;
}

export default function StatsCard({ title, value, icon, color, change, isLoading }: StatsCardProps) {
  if (isLoading) {
    return (
      <div className="card">
        <div className="card-body">
          <div className="d-flex align-items-center">
            <div className={`bg-${color} rounded-circle p-3 me-3`}>
              <div className="spinner-border spinner-border-sm text-white" role="status">
                <span className="visually-hidden">Loading...</span>
              </div>
            </div>
            <div className="flex-grow-1">
              <div className="placeholder-glow">
                <span className="placeholder col-6"></span>
                <h4 className="placeholder col-8"></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="card h-100">
      <div className="card-body">
        <div className="d-flex align-items-center">
          <div className={`bg-${color} rounded-circle p-3 me-3`}>
            <i className={`${icon} text-white fs-5`}></i>
          </div>
          <div className="flex-grow-1">
            <h6 className="card-title text-muted mb-1">{title}</h6>
            <h4 className="mb-0">{value}</h4>
            {change && (
              <small className={`text-${change.startsWith('+') ? 'success' : 'danger'}`}>
                <i className={`bi bi-arrow-${change.startsWith('+') ? 'up' : 'down'} me-1`}></i>
                {change}
              </small>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}