import React, { useState, useRef } from 'react';
import { Button } from './button';
import { Input } from './input';
import { useToast } from '@/hooks/use-toast';

interface FileUploadProps {
  fieldName: string;
  label: string;
  accept?: string;
  maxSize?: number; // in MB
  currentFile?: string;
  onFileSelect: (file: File | null, fieldName: string) => void;
  onFileRemove?: (fieldName: string) => void;
  disabled?: boolean;
  className?: string;
}

export function FileUpload({
  fieldName,
  label,
  accept = "image/*",
  maxSize = 5,
  currentFile,
  onFileSelect,
  onFileRemove,
  disabled = false,
  className = ""
}: FileUploadProps) {
  const [preview, setPreview] = useState<string | null>(currentFile || null);
  const [isDragging, setIsDragging] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const { toast } = useToast();

  const handleFileSelect = (file: File) => {
    // Validate file size
    if (file.size > maxSize * 1024 * 1024) {
      toast({
        title: "Error",
        description: `File size must be less than ${maxSize}MB`,
        variant: "destructive",
      });
      return;
    }

    // Validate file type
    if (accept !== "*" && !file.type.match(accept.replace("*", ".*"))) {
      toast({
        title: "Error",
        description: "Invalid file type",
        variant: "destructive",
      });
      return;
    }

    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
      setPreview(e.target?.result as string);
    };
    reader.readAsDataURL(file);

    onFileSelect(file, fieldName);
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      handleFileSelect(file);
    }
  };

  const handleDrop = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
    
    const file = e.dataTransfer.files[0];
    if (file) {
      handleFileSelect(file);
    }
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(true);
  };

  const handleDragLeave = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
  };

  const handleRemove = () => {
    setPreview(null);
    onFileSelect(null, fieldName);
    if (onFileRemove) {
      onFileRemove(fieldName);
    }
    if (fileInputRef.current) {
      fileInputRef.current.value = '';
    }
  };

  const handleClick = () => {
    fileInputRef.current?.click();
  };

  return (
    <div className={`space-y-2 ${className}`}>
      <label className="block text-sm font-medium text-gray-700">
        {label}
      </label>
      
      <div
        className={`
          relative border-2 border-dashed rounded-lg p-4 text-center cursor-pointer transition-colors
          ${isDragging ? 'border-blue-400 bg-blue-50' : 'border-gray-300 hover:border-gray-400'}
          ${disabled ? 'opacity-50 cursor-not-allowed' : ''}
        `}
        onDrop={handleDrop}
        onDragOver={handleDragOver}
        onDragLeave={handleDragLeave}
        onClick={!disabled ? handleClick : undefined}
      >
        <Input
          ref={fileInputRef}
          type="file"
          accept={accept}
          onChange={handleFileChange}
          className="hidden"
          disabled={disabled}
        />

        {preview ? (
          <div className="space-y-2">
            <div className="relative inline-block">
              <img
                src={preview}
                alt="Preview"
                className="max-w-full max-h-32 rounded object-cover"
              />
              {!disabled && (
                <Button
                  type="button"
                  variant="destructive"
                  size="sm"
                  className="absolute -top-2 -right-2 h-6 w-6 rounded-full p-0"
                  onClick={(e) => {
                    e.stopPropagation();
                    handleRemove();
                  }}
                >
                  ×
                </Button>
              )}
            </div>
            <p className="text-xs text-gray-500">Click to change or drag new file</p>
          </div>
        ) : (
          <div className="space-y-2">
            <div className="mx-auto h-12 w-12 text-gray-400">
              <svg
                className="h-full w-full"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 48 48"
              >
                <path
                  d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                  strokeWidth={2}
                  strokeLinecap="round"
                  strokeLinejoin="round"
                />
              </svg>
            </div>
            <div>
              <p className="text-sm text-gray-600">
                <span className="font-medium text-blue-600">Click to upload</span> or drag and drop
              </p>
              <p className="text-xs text-gray-500">
                {accept === "image/*" ? "PNG, JPG, GIF up to" : "Files up to"} {maxSize}MB
              </p>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
