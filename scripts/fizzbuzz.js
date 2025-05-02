document.addEventListener("DOMContentLoaded", function(event)
{
    document.querySelector(`button[type="submit"]`).addEventListener('click', function(event)
    {

        console.log(document.querySelector(`form`).checkValidity());
        console.log(document.querySelector(`button[type="submit"]`).checkValidity());
        if (document.querySelector(`form`).checkValidity())
        {
            event.preventDefault(); // Prevent form submission

            // Name fields
            var firstName = document.getElementById('first-name').value;
            const middleInitial = document.getElementById('middle-initial').value;
            const lastName = document.getElementById('last-name').value;

            // Assemble Welcome message
            // Correct capitalization if needed
            firstName = firstName.replace(/^./, (char) => (char.toUpperCase()));
            // (sort of) Accounts for multiword last names where the first word should not be capitalized
            if (! /[A-Z]/.test(lastName)) lastName.replace(/^./, (char) => char.toUpperCase());
            if (middleInitial) middleInitial = middleInitial.replace(/^./, (char) => char.toUpperCase());
            
            var welcomeMessage = "Welcome, " + firstName;

            if (middleInitial) welcomeMessage += " " + middleInitial + ".";
            if (lastName) welcomeMessage += " " + lastName;

            const messageHeader = document.querySelector('main section:last-child h3');
            welcomeMessage += "! Here are your results.";
            messageHeader.innerHTML = welcomeMessage;

            // Fizz Buzz word/number pairs
            const word1 = document.getElementById('word1').value;
            const divisor1 = parseInt(document.getElementById('divisor1').value, 10);

            const word2 = document.getElementById('word2').value;
            const divisor2 = parseInt(document.getElementById('divisor2').value, 10);

            const word3 = document.getElementById('word3').value;
            const divisor3 = parseInt(document.getElementById('divisor3').value, 10);

            // Default word and count limit
            const defaultWord = document.getElementById('main-word').value;
            const countLimit = parseInt(document.getElementById('count-limit').value, 10);
            
            // put results in an unordered list
            const list = document.querySelector('section ul');
            list.innerHTML = ''; // Clear previous results
            
            // output word if number can be divided by the word's given divisor
            for (let i = 1; i <= countLimit; i++) 
            {
                let output = '';
        
                if (divisor1 && i % divisor1 === 0) output += word1 + " ";
                if (divisor2 && i % divisor2 === 0) output += word2 + " ";
                if (divisor3 && i % divisor3 === 0) output += word3 + " ";
        
                if (!output) output = defaultWord;
        
                // add to list
                const listItem = document.createElement('li');
                listItem.textContent = i + ": " + output;
                list.appendChild(listItem);
            } // End of for loop
        }
    }); // 

    document.querySelector(`button[type="reset"]`).addEventListener('click', function(event)
    {
        document.querySelector('main section ul').innerHTML = "";
    });

}); // End of DOMContentLoaded event