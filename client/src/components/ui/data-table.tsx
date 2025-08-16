import React from 'react';
import { Button } from './button';

export interface Column<T> {
  key: keyof T | string;
  header: string;
  render?: (value: any, row: T) => React.ReactNode;
  sortable?: boolean;
  width?: string;
}

export interface DataTableProps<T> {
  data: T[];
  columns: Column<T>[];
  loading?: boolean;
  onEdit?: (row: T) => void;
  onDelete?: (row: T) => void;
  onAdd?: () => void;
  addButtonText?: string;
  emptyMessage?: string;
  className?: string;
}

export function DataTable<T extends Record<string, any>>({
  data,
  columns,
  loading = false,
  onEdit,
  onDelete,
  onAdd,
  addButtonText = "Add New",
  emptyMessage = "No data available",
  className = ""
}: DataTableProps<T>) {
  const getValue = (row: T, key: string) => {
    return key.split('.').reduce((obj, k) => obj?.[k], row);
  };

  if (loading) {
    return (
      <div className={`space-y-4 ${className}`}>
        <div className="flex justify-between items-center">
          <div className="h-8 bg-gray-200 rounded w-32 animate-pulse"></div>
          {onAdd && (
            <div className="h-10 bg-gray-200 rounded w-24 animate-pulse"></div>
          )}
        </div>
        <div className="border rounded-lg overflow-hidden">
          <div className="bg-gray-50 p-4">
            <div className="grid grid-cols-4 gap-4">
              {Array.from({ length: 4 }).map((_, i) => (
                <div key={i} className="h-4 bg-gray-200 rounded animate-pulse"></div>
              ))}
            </div>
          </div>
          {Array.from({ length: 3 }).map((_, i) => (
            <div key={i} className="border-t p-4">
              <div className="grid grid-cols-4 gap-4">
                {Array.from({ length: 4 }).map((_, j) => (
                  <div key={j} className="h-4 bg-gray-200 rounded animate-pulse"></div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className={`space-y-4 ${className}`}>
      {/* Header with Add Button */}
      {onAdd && (
        <div className="flex justify-end">
          <Button onClick={onAdd} className="flex items-center gap-2">
            <i className="bi bi-plus"></i>
            {addButtonText}
          </Button>
        </div>
      )}

      {/* Table */}
      <div className="border rounded-lg overflow-hidden">
        {data.length === 0 ? (
          <div className="p-8 text-center text-gray-500">
            <div className="mx-auto h-12 w-12 text-gray-400 mb-4">
              <i className="bi bi-inbox text-4xl"></i>
            </div>
            <p>{emptyMessage}</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-gray-50">
                <tr>
                  {columns.map((column, index) => (
                    <th
                      key={index}
                      className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      style={{ width: column.width }}
                    >
                      {column.header}
                    </th>
                  ))}
                  {(onEdit || onDelete) && (
                    <th className="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  )}
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {data.map((row, rowIndex) => (
                  <tr key={rowIndex} className="hover:bg-gray-50">
                    {columns.map((column, colIndex) => (
                      <td key={colIndex} className="px-4 py-3 text-sm text-gray-900">
                        {column.render
                          ? column.render(getValue(row, column.key as string), row)
                          : getValue(row, column.key as string) || '-'
                        }
                      </td>
                    ))}
                    {(onEdit || onDelete) && (
                      <td className="px-4 py-3 text-right text-sm">
                        <div className="flex justify-end gap-2">
                          {onEdit && (
                            <Button
                              variant="outline"
                              size="sm"
                              onClick={() => onEdit(row)}
                              className="flex items-center gap-1"
                            >
                              <i className="bi bi-pencil"></i>
                              Edit
                            </Button>
                          )}
                          {onDelete && (
                            <Button
                              variant="destructive"
                              size="sm"
                              onClick={() => onDelete(row)}
                              className="flex items-center gap-1"
                            >
                              <i className="bi bi-trash"></i>
                              Delete
                            </Button>
                          )}
                        </div>
                      </td>
                    )}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}

// Utility function to create image renderer for table columns
export const createImageRenderer = (altText: string = "Image") => {
  return (value: string) => {
    if (!value) return '-';
    return (
      <img
        src={value}
        alt={altText}
        className="h-10 w-10 rounded object-cover"
        onError={(e) => {
          (e.target as HTMLImageElement).style.display = 'none';
        }}
      />
    );
  };
};

// Utility function to create badge renderer for status columns
export const createBadgeRenderer = (colorMap: Record<string, string> = {}) => {
  return (value: string) => {
    if (!value) return '-';
    const color = colorMap[value] || 'bg-gray-100 text-gray-800';
    return (
      <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${color}`}>
        {value}
      </span>
    );
  };
};

// Utility function to create date renderer
export const createDateRenderer = (format: 'date' | 'datetime' | 'relative' = 'date') => {
  return (value: string | number) => {
    if (!value) return '-';
    const date = new Date(typeof value === 'number' ? value * 1000 : value);
    
    if (format === 'relative') {
      const now = new Date();
      const diffMs = now.getTime() - date.getTime();
      const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
      
      if (diffDays === 0) return 'Today';
      if (diffDays === 1) return 'Yesterday';
      if (diffDays < 7) return `${diffDays} days ago`;
      if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
      return `${Math.floor(diffDays / 30)} months ago`;
    }
    
    if (format === 'datetime') {
      return date.toLocaleString();
    }
    
    return date.toLocaleDateString();
  };
};
