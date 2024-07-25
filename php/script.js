$(document).ready(function() {

    $(document).ready(function() {

        // Function to fetch and display room information on modal open
        $(document).on('click', '.info-btn', function() {
            var roomId = $(this).data('room-id');
            
            $.post('process.php', { roomInfoId: roomId }, function(response) {
                var roomInfo = JSON.parse(response);
                
                if (roomInfo.roomInfo && roomInfo.roomInfo.length > 0) {
                    var detailsHtml = '<h2 style="color: #2b4c7e; font-size: 24px; text-align: center;">Room Information</h2>';
                    detailsHtml += '<p class="info-detail"><strong>Room Code:</strong> ' + roomInfo.roomInfo[0].RoomID + '</p>';
                    detailsHtml += '<p class="info-detail"><strong>Availability:</strong> ' + roomInfo.roomInfo[0].NumofTen + ' / ' + roomInfo.roomInfo[0].Capacity + '</p>';
                    detailsHtml += '<p class="info-detail"><strong>Status:</strong> ' + roomInfo.roomInfo[0].RoomStatus + '</p>';
                    detailsHtml += '<h3 style="margin-top: 20px; text-align: left;">Occupants:</h3>';
                    
                    if (roomInfo.roomInfo[0].TenFname) {
                        detailsHtml += '<table id="occupantsTable">';
                        detailsHtml += '<thead><tr><th>Tenant Name</th><th>Start Date</th><th>End Date</th></tr></thead>';
                        detailsHtml += '<tbody>';
                        roomInfo.roomInfo.forEach(function(info) {
                            detailsHtml += '<tr>';
                            detailsHtml += '<td>' + info.TenFname + ' ' + info.TenLname + '</td>';
                            detailsHtml += '<td>' + info.OccDateStart + '</td>';
                            detailsHtml += '<td>' + info.OccDateEnd + '</td>';
                            detailsHtml += '</tr>';
                        });
                        detailsHtml += '</tbody></table>';
                    } else {
                        detailsHtml += '<p class="info-detail">No occupants currently in this room.</p>';
                    }
                    
                    // Add Edit and Delete buttons
                    detailsHtml += '<div class="button-container" style="text-align: center; margin-top: 20px;">';
                    detailsHtml += '<button class="edit-btn" data-room-id="' + roomId + '" style="margin-right: 10px;">Edit</button>';
                    detailsHtml += '<button class="delete-btn" data-room-id="' + roomId + '">Delete</button>';
                    detailsHtml += '</div>';
                    
                    $('#roomDetails').html(detailsHtml);
                } else {
                    $('#roomDetails').html('<p class="info-detail">Room information not found.</p>');
                }
                
                // Show modal after populating room details
                $('#moreInfoModal').css('display', 'block');
            });
        });
    
        // Event handler for edit button
// Fetch room details for editing
$(document).on('click', '.edit-btn', function() {
    var roomId = $(this).data('room-id');
    
    $.post('process.php', { roomInfoId: roomId }, function(response) {
        var roomInfo = JSON.parse(response);
        $('#moreInfoModal').css('display', 'none');
        
        if (roomInfo.roomInfo && roomInfo.roomInfo.length > 0) {
            var room = roomInfo.roomInfo[0];
            var editHtml = '<h2 style="color: #2b4c7e; font-size: 24px; text-align: center;">Edit Room Information</h2>';
            editHtml += '<form id="editRoomForm">';
            editHtml += '<label for="roomId">Room Code:</label>';
            editHtml += '<input type="text" id="newroomId" name="roomId" value="' + room.RoomID + '"><br>';

            editHtml += '<label for="roomType">Room Type:</label>';
            editHtml += '<select id="roomType" name="roomType">';
            editHtml += '<option value="1"' + (room.RoomType === 'Empty' ? ' selected' : '') + '>Empty</option>';
            editHtml += '<option value="2"' + (room.RoomType === 'Shared' ? ' selected' : '') + '>Shared</option>';
            editHtml += '<option value="3"' + (room.RoomType === 'Rented' ? ' selected' : '') + '>Rented</option>';
            editHtml += '</select><br>';
            editHtml += '<label for="capacity">Capacity:</label>';
            editHtml += '<select id="newcapacity" name="capacity">';
            editHtml += '<option value="1"' + (room.Capacity === '4' ? ' selected' : '') + '>4</option>';
            editHtml += '<option value="2"' + (room.Capacity === '6' ? ' selected' : '') + '>6</option>';
            editHtml += '</select><br>';
            editHtml += '<button type="submit" class="save-edit-btn" data-room-id="' + room.RoomID + '" style="margin-top: 20px;">Save Changes</button>';
            editHtml += '</form>';
            
            $('#editRoomDetails').html(editHtml);
            $('#editRoomModal').css('display', 'block');
        }
    });
});
$(document).on('submit', '#editRoomForm', function(e) {
    e.preventDefault();

    var oldRoomId = $('.save-edit-btn').data('room-id'); // Retrieve old room ID from button data
    var newRoomId = $('#newroomId').val(); // Retrieve new room ID from input
    var roomType = $('#roomType').val(); // Retrieve room type from select
    var capacity = $('#newcapacity').val(); // Retrieve capacity from select
    
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


$(document).ready(function() {
    $('#roomId').on('input', function() {
        console.log("Room ID field value:", $(this).val());
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

        
        




    
        // Event handler for close button in the more info modal
        $(document).on('click', '#moreInfoModal .close', function() {
            $('#moreInfoModal').css('display', 'none');
        });
    
        // Event handler for close button in the edit modal
        $(document).on('click', '#editRoomModal .close', function() {
            $('#editRoomModal').css('display', 'none');
        });
    
    });

    
    
    
        $(document).on('click', '.close', function() {
            $('#moreInfoModal').css('display', 'none');
            $('#addRoomModal').css('display', 'none');
        });
    
    
        $('#roomAvailability').change(function() {
            var selectedOption = $(this).val();
            $('#roomLogsContent').html(''); // Clear old data
            
    
            $.get('process.php', { availability: selectedOption }, function(response) {
                $('#roomLogsContent').html(response);
            });
        });
    
        // Initial fetch for all rooms
        printRooms('all'); // Initial load of rooms
    
        // Handle sort order change (assuming #sortOrder is the ID of your sort select)
        $('#sortOrder').on('change', function() {
            var availability = $('#roomAvailability').val();
            $('#roomLogsContent').html(''); // Clear old data
            
            // AJAX call to fetch rooms with updated sort order
            $.get('process.php', { availability: availability }, function(response) {
                $('#roomLogsContent').html(response);
            });
        });
    
        // Modal handling for Add Room
        var addRoomModal = document.getElementById("addRoomModal");
        var moreInfoModal = document.getElementById("moreInfoModal"); // Ensure moreInfoModal is declared here
    
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
    
    });
    
    // Function to print rooms based on availability
    function printRooms(availability) {
        $.get('process.php', { availability: availability }, function(response) {
            $('#roomLogsContent').html(response);
        });
    }