document.addEventListener("DOMContentLoaded", function(event)
{
    const editButtons = document.querySelectorAll("button[name=\"toggleEdit\"]");
            
            
    // Show popup when the label is hovered or focused
    function showPopup(label) 
    {
        image = label.target.querySelector("img");
        
        // Create popup 
        const popup = document.createElement('div');
        popup.className = 'image-popup';
        const largeImg = document.createElement('img');
        largeImg.src = image.src;  
        largeImg.alt = image.alt;
        popup.appendChild(largeImg); 

        // Append popup
        document.body.appendChild(popup);

        // Position the popup near image
        const rect = image.getBoundingClientRect();
        popup.style.left = `${rect.right + 10 + window.scrollX}px`;
        popup.style.top = `${rect.bottom + window.scrollY - (rect.height * 2)}px`;

        popup.style.display = 'block';

        // Store reference to the popup for removal later
        image._popup = popup;
    }

    // Hide popup and remove it from DOM
    function hidePopup(label) 
    {
        image = label.target.querySelector("img");
        if (image._popup) 
        {
            document.body.removeChild(image._popup);
            image._popup = null;
        }
    }
    
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

            // add image popup event listener
            label = formRow.querySelector("label");

            // Add event listeners for mouse and keyboard
            label.addEventListener('mouseenter', showPopup);
            label.addEventListener('mouseleave', hidePopup);
            label.addEventListener('focus', showPopup, true);
            label.addEventListener('blur', hidePopup, true);
            

        }); // end edit button foreach

        const clearFileButton = document.querySelector("button[name='clearFile']");

        clearFileButton.addEventListener('click', function(event)
        {
            document.querySelector("input[name='image']").value = '';
            document.querySelector("label[for=image]").innerHTML = 'Upload';
            clearFileButton.setAttribute("style", "display: none");
            document.querySelector("label[for=image]").setAttribute("style", "display: inline-block;");

        });

        // replace label text with selected filename when adding new car
        document.querySelector("#image").addEventListener('change', function(event)
        {
            document.querySelector("label[for=image]").innerHTML = event.target.value.split('\\').pop();
            clearFileButton.setAttribute("style", "display: inline-block;");
            document.querySelector("label[for=image]").setAttribute("style", "display: contents;");

            
        });

        // dynamically size input width in display table
        function inputResize(event)
        {
/*             console.log("Resizing...");
            console.log(event.target.value.length);
            console.log("width: " + ((event.target.value.length + 1)) + "ch;"); */
            event.target.setAttribute("style", "width: " + (event.target.value.length + 2) + "ch;");
        }

        // set table col widths + attatch listener to resize on new input
        document.querySelectorAll('form[name="edit"] thead tr th').forEach((headerCell, i) =>
        {
            selector = 'form[name="edit"] tbody tr td:nth-of-type(' + (i + 1) + '):not(.actionButtons) input';
            document.querySelectorAll(selector).forEach((cell) =>
            {
                //adjust width if content of cell is longer than header text
                if (cell.value.length >= headerCell.innerHTML.length)
                {
                    cell.setAttribute("style", "width: " + (cell.value.length + 2) + "ch;");
                }
                else
                {
                    cell.setAttribute("style", "width: " + (headerCell.innerHTML.length + 4) + "ch;");
                }
                cell.addEventListener('change',inputResize);
            });
        });
});