import React, { useState, useRef, useEffect } from 'react';
import { Input } from './input';

interface ColorPickerProps {
  value: string;
  onChange: (color: string) => void;
  label?: string;
  disabled?: boolean;
  className?: string;
}

const predefinedColors = [
  '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
  '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
  '#F8C471', '#82E0AA', '#F1948A', '#85C1E9', '#D7BDE2',
  '#A3E4D7', '#F9E79F', '#FADBD8', '#D5DBDB', '#2C3E50'
];

export function ColorPicker({
  value,
  onChange,
  label,
  disabled = false,
  className = ""
}: ColorPickerProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [inputValue, setInputValue] = useState(value || '#000000');
  const dropdownRef = useRef<HTMLDivElement>(null);
  const buttonRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    setInputValue(value || '#000000');
  }, [value]);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        dropdownRef.current &&
        !dropdownRef.current.contains(event.target as Node) &&
        buttonRef.current &&
        !buttonRef.current.contains(event.target as Node)
      ) {
        setIsOpen(false);
      }
    };

    if (isOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }

    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [isOpen]);

  const handleColorSelect = (color: string) => {
    setInputValue(color);
    onChange(color);
    setIsOpen(false);
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const newValue = e.target.value;
    setInputValue(newValue);
    
    // Validate hex color format
    if (/^#[0-9A-F]{6}$/i.test(newValue)) {
      onChange(newValue);
    }
  };

  const handleInputBlur = () => {
    // If the input is not a valid hex color, revert to the current value
    if (!/^#[0-9A-F]{6}$/i.test(inputValue)) {
      setInputValue(value || '#000000');
    }
  };

  const isValidColor = /^#[0-9A-F]{6}$/i.test(inputValue);

  return (
    <div className={`space-y-2 ${className}`}>
      {label && (
        <label className="block text-sm font-medium text-gray-700">
          {label}
        </label>
      )}
      
      <div className="relative">
        <div className="flex gap-2">
          {/* Color Preview Button */}
          <button
            ref={buttonRef}
            type="button"
            onClick={() => !disabled && setIsOpen(!isOpen)}
            disabled={disabled}
            className={`
              w-12 h-10 rounded border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
              ${disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-gray-400'}
            `}
            style={{ backgroundColor: isValidColor ? inputValue : '#ffffff' }}
          >
            {!isValidColor && (
              <span className="text-xs text-gray-500">?</span>
            )}
          </button>

          {/* Hex Input */}
          <Input
            type="text"
            value={inputValue}
            onChange={handleInputChange}
            onBlur={handleInputBlur}
            placeholder="#000000"
            disabled={disabled}
            className={`flex-1 ${!isValidColor ? 'border-red-300' : ''}`}
            maxLength={7}
          />
        </div>

        {/* Color Picker Dropdown */}
        {isOpen && !disabled && (
          <div
            ref={dropdownRef}
            className="absolute top-full left-0 mt-1 p-3 bg-white border border-gray-200 rounded-lg shadow-lg z-50 w-64"
          >
            <div className="space-y-3">
              {/* Predefined Colors */}
              <div>
                <h4 className="text-xs font-medium text-gray-700 mb-2">Predefined Colors</h4>
                <div className="grid grid-cols-5 gap-2">
                  {predefinedColors.map((color) => (
                    <button
                      key={color}
                      type="button"
                      onClick={() => handleColorSelect(color)}
                      className={`
                        w-8 h-8 rounded border-2 hover:scale-110 transition-transform
                        ${inputValue.toLowerCase() === color.toLowerCase() 
                          ? 'border-gray-800 ring-2 ring-blue-500' 
                          : 'border-gray-300 hover:border-gray-400'
                        }
                      `}
                      style={{ backgroundColor: color }}
                      title={color}
                    />
                  ))}
                </div>
              </div>

              {/* Native Color Picker */}
              <div>
                <h4 className="text-xs font-medium text-gray-700 mb-2">Custom Color</h4>
                <input
                  type="color"
                  value={isValidColor ? inputValue : '#000000'}
                  onChange={(e) => handleColorSelect(e.target.value)}
                  className="w-full h-10 rounded border border-gray-300 cursor-pointer"
                />
              </div>

              {/* Recent/Common Colors */}
              <div className="pt-2 border-t border-gray-200">
                <div className="flex justify-between">
                  <button
                    type="button"
                    onClick={() => handleColorSelect('#FFFFFF')}
                    className="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded"
                  >
                    White
                  </button>
                  <button
                    type="button"
                    onClick={() => handleColorSelect('#000000')}
                    className="px-3 py-1 text-xs bg-gray-800 text-white hover:bg-gray-700 rounded"
                  >
                    Black
                  </button>
                  <button
                    type="button"
                    onClick={() => handleColorSelect('#808080')}
                    className="px-3 py-1 text-xs bg-gray-500 text-white hover:bg-gray-600 rounded"
                  >
                    Gray
                  </button>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>

      {!isValidColor && inputValue && (
        <p className="text-xs text-red-600">
          Please enter a valid hex color (e.g., #FF0000)
        </p>
      )}
    </div>
  );
}
