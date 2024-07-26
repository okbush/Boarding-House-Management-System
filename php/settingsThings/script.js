
$(document).ready(function() {

    // event listener for add staff
    $(document).on('click', '.add-staff-button', function() {
        $('#addStaffModal').css('display', 'block');
    
        // ajax request for PHP function
        $.post('process.php', 
            { action: 'addStaffModal' }, 
            function(response) {
                console.log('Request completed.');
                $('#addStaffModal .modal-content #modal-body').html(response);  // Corrected this line
            }
            ).fail(function() {
                alert('Failed to retrieve room information.');
        });
    });
    
    // Event listener for rates button
    $(document).on('click', '.rates', function() {

        $('#ratesModal').css('display', 'block');

        // AJAX request for PHP function
        $.post('process.php', { action: 'getRatesContent' }, function(response) {
            console.log('Request completed. Response:', response);
            $('#ratesModal .modal-content #modal-body').html(response);
        }).fail(function() {
            alert('Failed to retrieve rates information.');
        });
    });


    $(document).on('click', '.close', function() {
        $('#ratesModal').css('display', 'none');
    });
    
    $(document).on('click', '.close', function() {
        $('#addStaffModal').css('display', 'none');
    });
    

    
        // Event listener for edit buttons within the rates modal
    $(document).on('click', '.edit-button', function() {
        var roomType = $(this).data('room-type');
        var rateAmount = $(this).siblings('.rate-container').find('span').text().replace('$', '');

        // Populate the edit modal with current values
        $('#editRoomType').val(roomType);
        $('#editRate').val(rateAmount);
        $('#originalRoomType').val(roomType);

        // Show the edit modal
        $('#editModal').css('display', 'block');
    });

// Event listener for form submission in the edit modal
$(document).on('submit', '#addStaffForm', function(event) {
    event.preventDefault(); // Prevent default form submission

    $.ajax({
        url: 'process.php', // URL of the PHP script that handles the insertion
        type: 'POST',
        data: $(this).serialize() + '&action=addstaffform', // Serialize form data and add action
        dataType: 'json', // Expect JSON response
        success: function(response) {
            if (response.success) {
                $('#addStaffform').css('display', 'none'); // Hide the modal
                $('#addStaffModal').css('display', 'none');
                alert('Staff successfully added!');
                // Optionally, you can add code here to refresh the staff list or update the UI
            } else {
                alert('Failed to add staff: ' + response.error);
            }
        },
        error: function() {
            alert('Failed to add staff.');
        }
    });
});
$(document).on('click', '#editModal .close', function() {
    $('#editModal').css('display', 'none');
});




    $(document).on('click', '.Change-details', function() {
        $('#userdetails').css('display', 'block');
    });

    // Close the modal when the close button is clicked
    $(document).on('click', '.close', function() {
        $('#userdetails').css('display', 'none');
    });

// Handle form submission via AJAX
$(document).on('submit', '#changeDetailsForm', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = $(this).serialize() + '&action=updateDetails';

    $.post('process.php', formData, function(response) {
        console.log('Server response:', response); // Log the raw response for debugging




                $('#userdetails').css('display', 'none'); // Hide the modal
                alert('Details successfully updated!');


        
    });
    
});

    
    
    });