document.addEventListener("DOMContentLoaded", function(event)
{
    const editButtons = document.querySelectorAll("button[name=\"toggleEdit\"]");
            
            /* edit, submit/update, delete, and cancel events for buttons in car display table */
            editButtons.forEach((button) => 
            {
                
                const formRow = button.closest('tr');
                const editableFields = formRow.querySelectorAll('input[type="text"], input[type="file"]');
                const updateButton = formRow.querySelector('button[name="update"]');
                const deleteButton = formRow.querySelector('button[name="delete"');
                const cancelButton = formRow.querySelector('button[name="cancelEdit');
                const displayedImage = formRow.querySelector('label img');
                

                button.addEventListener('click', function(event)
                {
                    console.log(updateButton); 

                    updateButton.setAttribute("style", "display: inline-block;");
                    cancelButton.setAttribute("style", "display: inline-block");
                    displayedImage.setAttribute("style", "cursor: pointer");

                    button.setAttribute("style", "display: none");
                    deleteButton.setAttribute("style", "display: none;");
                    editableFields.forEach((field) =>
                    {
                        field.removeAttribute('disabled');
                    }); // end editableFields foreach
                }); // end edit button listener

                formRow.querySelector("button[name=\"cancelEdit\"]").addEventListener('click', function(event)
                {
                    
                    updateButton.setAttribute("style", "display: none;");
                    cancelButton.setAttribute("style", "display: none;");
                    button.setAttribute("style", "display: inline-block");
                    deleteButton.setAttribute("style", "display: inline-block;");

                    editableFields.forEach((field) =>
                    {
                        field.setAttribute('disabled', "");
                    }); // end editableFields foreach

                }); // end cancel button listener
            }); // end edit button foreach
});