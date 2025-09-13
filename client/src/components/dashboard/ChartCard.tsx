import React from "react";

interface ChartCardProps {
  title: string;
  data: any[];
  type: 'line' | 'bar' | 'pie';
  isLoading?: boolean;
}

export default function ChartCard({ title, data, type, isLoading }: ChartCardProps) {
  if (isLoading) {
    return (
      <div className="card h-100">
        <div className="card-header">
          <h5 className="card-title mb-0">{title}</h5>
        </div>
        <div className="card-body d-flex align-items-center justify-content-center">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    );
  }

  // Simple bar chart representation for demo
  const maxValue = Math.max(...data.map(item => 
    Object.values(item).find(val => typeof val === 'number') as number || 0
  ));

  const renderBarChart = () => (
    <div className="d-flex align-items-end justify-content-between" style={{ height: '200px' }}>
      {data.map((item, index) => {
        const value = Object.values(item).find(val => typeof val === 'number') as number || 0;
        const height = (value / maxValue) * 160;
        const label = Object.values(item)[0] as string;
        
        return (
          <div key={index} className="d-flex flex-column align-items-center">
            <div 
              className="bg-primary rounded-top"
              style={{ 
                width: '30px', 
                height: `${height}px`,
                minHeight: '10px'
              }}
              title={`${label}: ${value}`}
            ></div>
            <small className="mt-2 text-muted text-center" style={{ fontSize: '10px' }}>
              {label}
            </small>
          </div>
        );
      })}
    </div>
  );

  const renderLineChart = () => (
    <div className="position-relative" style={{ height: '200px' }}>
      <svg width="100%" height="200" className="overflow-visible">
        <defs>
          <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stopColor="#007bff" stopOpacity="0.3" />
            <stop offset="100%" stopColor="#007bff" stopOpacity="0" />
          </linearGradient>
        </defs>
        
        {/* Generate path for line chart */}
        {data.length > 0 && (
          <>
            <path
              d={data.map((item, index) => {
                const value = Object.values(item).find(val => typeof val === 'number') as number || 0;
                const x = (index / (data.length - 1)) * 100;
                const y = 100 - (value / maxValue) * 80;
                return `${index === 0 ? 'M' : 'L'} ${x}% ${y}%`;
              }).join(' ')}
              fill="none"
              stroke="#007bff"
              strokeWidth="3"
              vectorEffect="non-scaling-stroke"
            />
            <path
              d={`${data.map((item, index) => {
                const value = Object.values(item).find(val => typeof val === 'number') as number || 0;
                const x = (index / (data.length - 1)) * 100;
                const y = 100 - (value / maxValue) * 80;
                return `${index === 0 ? 'M' : 'L'} ${x}% ${y}%`;
              }).join(' ')} L 100% 100% L 0% 100% Z`}
              fill="url(#lineGradient)"
            />
          </>
        )}
      </svg>
      
      {/* Labels */}
      <div className="d-flex justify-content-between position-absolute bottom-0 w-100">
        {data.map((item, index) => (
          <small key={index} className="text-muted" style={{ fontSize: '10px' }}>
            {Object.values(item)[0] as string}
          </small>
        ))}
      </div>
    </div>
  );

  return (
    <div className="card h-100">
      <div className="card-header">
        <h5 className="card-title mb-0">{title}</h5>
      </div>
      <div className="card-body">
        {data.length === 0 ? (
          <div className="d-flex align-items-center justify-content-center h-100 text-muted">
            <div className="text-center">
              <i className="bi bi-graph-up fs-1 mb-3"></i>
              <p>No data available</p>
            </div>
          </div>
        ) : (
          <>
            {type === 'bar' && renderBarChart()}
            {type === 'line' && renderLineChart()}
            {type === 'pie' && (
              <div className="d-flex align-items-center justify-content-center h-100 text-muted">
                <p>Pie chart visualization coming soon</p>
              </div>
            )}
          </>
        )}
      </div>
    </div>
  );
}