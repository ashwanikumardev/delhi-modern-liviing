<?php
class ImageHelper {
    
    /**
     * Upload multiple images
     */
    public static function uploadImages($files, $uploadDir = 'uploads/rooms/') {
        $uploadedFiles = [];
        $errors = [];
        
        // Ensure upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Handle multiple files
        if (isset($files['name']) && is_array($files['name'])) {
            $fileCount = count($files['name']);
            
            for ($i = 0; $i < $fileCount; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $result = self::uploadSingleImage([
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i]
                    ], $uploadDir);
                    
                    if ($result['success']) {
                        $uploadedFiles[] = $result['filename'];
                    } else {
                        $errors[] = $result['error'];
                    }
                }
            }
        }
        
        return [
            'success' => count($uploadedFiles) > 0,
            'files' => $uploadedFiles,
            'errors' => $errors
        ];
    }
    
    /**
     * Upload single image
     */
    public static function uploadSingleImage($file, $uploadDir = 'uploads/rooms/') {
        // Validate file
        $validation = self::validateImage($file);
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }
        
        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('room_') . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Ensure upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Resize image if needed
            self::resizeImage($filepath, 1200, 800);
            
            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath
            ];
        }
        
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
    
    /**
     * Validate image file
     */
    public static function validateImage($file) {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'Upload error: ' . $file['error']];
        }
        
        // Check file size (5MB max)
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['valid' => false, 'error' => 'File size too large. Maximum 5MB allowed.'];
        }
        
        // Check file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['valid' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF allowed.'];
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowedExtensions)) {
            return ['valid' => false, 'error' => 'Invalid file extension.'];
        }
        
        // Verify it's actually an image
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return ['valid' => false, 'error' => 'File is not a valid image.'];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Resize image to fit within max dimensions
     */
    public static function resizeImage($filepath, $maxWidth = 1200, $maxHeight = 800) {
        $imageInfo = getimagesize($filepath);
        if (!$imageInfo) return false;
        
        list($width, $height, $type) = $imageInfo;
        
        // Check if resize is needed
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return true;
        }
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        // Create image resource based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($filepath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($filepath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($filepath);
                break;
            default:
                return false;
        }
        
        if (!$source) return false;
        
        // Create new image
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize image
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save resized image
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($destination, $filepath, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($destination, $filepath, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($destination, $filepath);
                break;
        }
        
        // Clean up memory
        imagedestroy($source);
        imagedestroy($destination);
        
        return true;
    }
    
    /**
     * Delete image file
     */
    public static function deleteImage($filename, $uploadDir = 'uploads/rooms/') {
        $filepath = $uploadDir . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
    
    /**
     * Get image URL
     */
    public static function getImageUrl($filename, $uploadDir = 'uploads/rooms/') {
        if (empty($filename)) {
            return url('assets/images/placeholder-room.jpg');
        }
        
        // Check if it's a URL (for demo data)
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }
        
        // Check if file exists
        $filepath = $uploadDir . $filename;
        if (file_exists($filepath)) {
            return url($filepath);
        }
        
        // Return placeholder if file doesn't exist
        return url('assets/images/placeholder-room.jpg');
    }
    
    /**
     * Create thumbnail
     */
    public static function createThumbnail($filepath, $thumbWidth = 300, $thumbHeight = 200) {
        $pathInfo = pathinfo($filepath);
        $thumbPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
        
        $imageInfo = getimagesize($filepath);
        if (!$imageInfo) return false;
        
        list($width, $height, $type) = $imageInfo;
        
        // Create source image
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($filepath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($filepath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($filepath);
                break;
            default:
                return false;
        }
        
        if (!$source) return false;
        
        // Create thumbnail
        $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
        
        // Preserve transparency
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $thumbWidth, $thumbHeight, $transparent);
        }
        
        // Resize to thumbnail
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
        
        // Save thumbnail
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($thumbnail, $thumbPath, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumbnail, $thumbPath, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($thumbnail, $thumbPath);
                break;
        }
        
        // Clean up
        imagedestroy($source);
        imagedestroy($thumbnail);
        
        return $thumbPath;
    }
}
?>
