import React from 'react';
import { Link } from '@inertiajs/react';
import PropTypes from 'prop-types';

export default function Pagination({ links, className = '' }) {
  if (!links || links.length === 0) return null;

  return (
    <div className={`flex flex-wrap justify-center gap-1 ${className}`}>
      {links.map((link, key) => {
        const isUrl = link.url !== null;
        const isActive = link.active;
        
        return isUrl ? (
          <Link
            key={key}
            href={link.url}
            className={`px-4 py-2 border rounded text-sm ${
              isActive
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
            }`}
            dangerouslySetInnerHTML={{ __html: link.label }}
          />
        ) : (
          <span
            key={key}
            className="px-4 py-2 border rounded text-sm bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed"
            dangerouslySetInnerHTML={{ __html: link.label }}
          />
        );
      })}
    </div>
  );
}

Pagination.propTypes = {
  links: PropTypes.arrayOf(
    PropTypes.shape({
      url: PropTypes.string,
      label: PropTypes.string.isRequired,
      active: PropTypes.bool.isRequired,
    })
  ).isRequired,
  className: PropTypes.string,
};
