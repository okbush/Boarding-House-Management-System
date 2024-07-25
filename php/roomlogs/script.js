    // ----------------------------------------- functions ---------------------------------------------------
    // Function to print rooms based on availability
    function printRooms(availability) {
        $.get('process.php', { availability: availability }, function(response) {
            $('#roomLogsContent').html(response);
        });
    }
    function printMoreinfo(){

    }


    $(document).ready(function() {
        printRooms('all');
        // ------------------------------- Event Listeners ----------------------------------------------------------------
        // Event handler for open button and populate for more info
$(document).on('click', '.info-btn', function() {
    var roomId = $(this).data('room-id');
    $('#moreInfoModal').css('display', 'block');

    // ajax request for PHP function
    $.post('process.php', 
        { action: 'getRoomInfo', roomId: roomId }, 
        function(response) {
            console.log('Request completed.');
            $('#moreInfoModal #roomDetails').html(response);
        }
    ).fail(function() {
        alert('Failed to retrieve room information.');
    });
});
// add room button
$('#addRoomBtn').on('click', function() {
    addRoomModal.style.display = "block";
});
// Form submission for Add Room
$('#addRoomForm').on('submit', function(event) {
    event.preventDefault();
    var formData = $(this).serialize();
            
    // AJAX call to add new room
    $.post('process.php', formData, function(response) {
    alert(response);
                
    // Reload rooms after adding a new room
    printRooms('all'); // Reload all rooms
    addRoomModal.style.display = "none";
    });
});
    

// Event handler for close button in the more info modal
$(document).on('click', '#moreInfoModal .close', function() {
    $('#moreInfoModal').css('display', 'none');
});

// Event handler for edit button in the more info modal
$(document).on('click', '.edit-btn', function() {
    var roomId = $(this).data('room-id');
    $('#editRoomModal').css('display', 'block');
    // ajax request for edit function to populate
    $.post('process.php', 
        { action: 'edit', roomId: roomId }, 
        function(response) {
            console.log('Request completed.');
            $('#editRoomModal #editRoomDetails').html(response);
        }
    ).fail(function() {
        alert('Failed to retrieve room information.');
    });
});
        // Event handler for delete button
        $(document).on('click', '.delete-btn', function() {
            var roomId = $(this).data('room-id');
            
            // Confirm deletion
            if (confirm('Are you sure you want to delete this room?')) {
                $.post('process.php', { deleteRoomId: roomId }, function(response) {
                    if (response === 'success') {
                        alert('Room deleted successfully.');
                        // Optionally, refresh the room list or close the modal
                        $('#moreInfoModal').css('display', 'none');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to delete room.');
                    }
                });
            }
        });
        // Event handler for close button in the edit modal
        $(document).on('click', '#editRoomModal .close', function() {
            $('#editRoomModal').css('display', 'none');
        });
        // Event handler for close button in the add room modal
        $(document).on('click', '#addRoomModal .close', function() {
            $('#addRoomModal').css('display', 'none');
        });
        // -------------------------------  Populate   --------------------------------------------------------------------------
        // Ajax for sort order
        $('#sortorder').on('change', function() {
            var availability = $('#sortorder').val();
            $('#roomLogsContent').html(''); 
            

            $.get('process.php', { availability: availability }, function(response) {
                $('#roomLogsContent').html(response);
            });
        });


        $(document).on('submit', '#editRoomForm', function(e) {
            e.preventDefault();
        
            var oldRoomId = $('.save-edit-btn').data('room-id'); // Retrieve old room ID from button data
            var newRoomId = $('#editRoomId').val(); 
            var roomType = $('#editRoomType').val();
            var capacity = $('#editCapacity').val(); 
            
            // Log values for debugging
            console.log("Submitting form for Room ID:", oldRoomId);
            console.log("New Room ID (input value):", newRoomId);
            console.log("Room Type (selected):", roomType);
            console.log("Capacity (selected):", capacity);
            
            // Ensure newRoomId and capacity are not empty
            if (!newRoomId || !capacity) {
                console.error("New Room ID or capacity is empty. Check if the form input fields are properly set.");
                return;
            }
            
            // Send data to server
            $.post('process.php', { 
                oldRoomId: oldRoomId,
                newRoomId: newRoomId,
                roomType: roomType,
                capacity: capacity
            }, function(response) {
                console.log("Response from server:", response);
                if (response === 'success') {
                    alert('Room information updated successfully.');
                    $('#editRoomModal').css('display', 'none');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Failed to update room information: ' + response);
                }
            });
        });


    });