import React from 'react';
import PropTypes from 'prop-types';

/**
 * Reusable table component.
 * Props:
 *  - columns: [{ header: string, accessor: string, render?: func }]
 *  - data: array of objects
 *  - onRowClick: function(record) optional
 *  - className: additional Tailwind classes
 */
export default function Table({ columns, data, onRowClick, className = '' }) {
  return (
    <div className={`overflow-x-auto ${className}`}>
      <table className="min-w-full divide-y divide-gray-200">
        <thead className="bg-gray-50">
          <tr>
            {columns.map((col, idx) => (
              <th
                key={idx}
                scope="col"
                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                {col.header}
              </th>
            ))}
          </tr>
        </thead>
        <tbody className="bg-white divide-y divide-gray-200">
          {data.map((row, rowIdx) => (
            <tr
              key={rowIdx}
              className={onRowClick ? 'cursor-pointer hover:bg-gray-100' : ''}
              onClick={onRowClick ? () => onRowClick(row) : undefined}
            >
              {columns.map((col, colIdx) => (
                <td key={colIdx} className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {col.render ? col.render(row[col.accessor], row) : row[col.accessor]}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

Table.propTypes = {
  columns: PropTypes.arrayOf(
    PropTypes.shape({
      header: PropTypes.string.isRequired,
      accessor: PropTypes.string.isRequired,
      render: PropTypes.func,
    })
  ).isRequired,
  data: PropTypes.arrayOf(PropTypes.object).isRequired,
  onRowClick: PropTypes.func,
  className: PropTypes.string,
};
