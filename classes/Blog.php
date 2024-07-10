<?php

class Blog
{
    private $conn;
    private $table = 'blogs';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new blog post
    public function create($user_id, $heading, $short_description, $long_description, $image) {

        $query = "INSERT INTO " . $this->table . " (user_id, heading, short_description, long_description, image) VALUES (:user_id, :heading, :short_description, :long_description, :image)";
        $imageName = $this->uploadImage($image);
        if ($imageName) {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':heading', $heading);
            $stmt->bindParam(':short_description', $short_description);
            $stmt->bindParam(':long_description', $long_description);
            $stmt->bindParam(':image', $imageName);

            if ($stmt->execute()) {
            return true;
            }

        }
        return false;
    
    }

    // Read a blog post by ID
    public function read($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    // Read all blog posts
    public function readAll()
    {
        $query = "SELECT b.*, u.username
                  FROM blogs b
                  LEFT JOIN users u ON b.user_id = u.id
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Update a blog post by ID
    public function update($id, $user_id, $heading, $short_description, $long_description, $image)
    {
        
        $imageName = $this->uploadImage($image);
        if ($imageName) {
            
            $sql = "UPDATE blogs SET heading = ?, short_description = ?, long_description = ?, image = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            
            // Fetch the current blog data to get the old image name
            $currentBlog = $this->read($id);
            $currentImageName = $currentBlog['image'];

            // Delete the old image file if it exists
            if (!empty($currentImageName) && file_exists('../uploads/' . $currentImageName)) {
            unlink('../uploads/' . $currentImageName);
            }


            return $stmt->execute([$heading, $short_description, $long_description, $imageName, $id]);
       
            
        }else {
            // If no new image is uploaded, update other fields only
            $query = "UPDATE " . $this->table . " SET user_id = :user_id, heading = :heading, short_description = :short_description, long_description = :long_description, modified_at = CURRENT_TIMESTAMP WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':heading', $heading);
            $stmt->bindParam(':short_description', $short_description);
            $stmt->bindParam(':long_description', $long_description);
            $stmt->bindParam(':id', $id);
            //print_r($stmt);exit;
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
            

        }
        

        return false;
    }

    // Delete a blog post by ID
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

         // Fetch the current blog data to get the old image name
         $currentBlog = $this->read($id);
         $currentImageName = $currentBlog['image'];
 
         // Delete the old image file if it exists
         if (!empty($currentImageName) && file_exists('../uploads/' . $currentImageName)) {
             unlink('../uploads/' . $currentImageName);
         }
 

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$status, $id])) {
            return true;
        }

        return false;
    }


    private function uploadImage($image) {
        if ($image['error'] == 0) {
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $imageName = 'IMG_' . round(microtime(true) * 1000) . '.' . $extension;
            $targetDir = "../uploads/";
            $targetFilePath = $targetDir . $imageName;

            if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                return $imageName;
            }
        }
        return false;
    }

    public function countBlogs() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table ;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getAllBlogs($offset, $limit) {
        $query = "SELECT * FROM " . $this->table . " LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllActiveBlogs($offset, $limit) {
        $query = "SELECT * FROM " . $this->table . "WHERE status = 1 LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBlogByHeading($heading) {
        $query = "SELECT * FROM " . $this->table . " WHERE heading = :heading LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':heading', $heading);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}



?>
