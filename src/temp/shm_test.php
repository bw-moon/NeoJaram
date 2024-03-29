﻿<?php
   
// Create 100 byte shared memory block with system id of 0xff3
$shm_id = shmop_open(0xff3, "c", 0644, 100);
if (!$shm_id) {
    echo "Couldn't create shared memory segment\n";
}

// Get shared memory block's size
$shm_size = shmop_size($shm_id);
echo "SHM Block Size: " . $shm_size . " has been created.\n";

// Lets write a test string into shared memory
$shm_bytes_written = shmop_write($shm_id, "my shared memory block", 0);
if ($shm_bytes_written != strlen("my shared memory block")) {
    echo "Couldn't write the entire length of data\n";
}

// Now lets read the string back
$my_string = shmop_read($shm_id, 0, $shm_size);
if (!$my_string) {
    echo "Couldn't read from shared memory block\n";
}
echo "The data inside shared memory was: " . $my_string . "\n";

//Now lets delete the block and close the shared memory segment
if (!shmop_delete($shm_id)) {
    echo "Couldn't mark shared memory block for deletion.";
}
shmop_close($shm_id);
   
?> 