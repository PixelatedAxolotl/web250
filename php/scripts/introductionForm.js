document.addEventListener("DOMContentLoaded", function(event)
{
    const courseList = document.getElementById('courseList');
    const addCourseButton = document.getElementById('addCourseButton');

    function populateDefault(defaultData)
    {
        for (const [elementId, defaultText] of Object.entries(defaultData)) 
        {
            const element = document.getElementById(elementId);
            if (!element) continue;
        
            element.value = defaultText;            
        } // End of for loop 
    } // End of populateDefault function

    function removeCourse(event)
    {
        let currentElement = event.target;

        console.log("REMOVING COURSE");
        console.log(event.target);

        while (currentElement)
        {
            const parent = currentElement.parentElement;

            if (parent) 
            {
                // Remove all siblings including the current element
                Array.from(parent.children).forEach((child) => 
                {
                    parent.removeChild(child);
                });

                // Stop if parent is a <div>
                if (parent.classList.contains('course'))
                {
                    parent.remove();
                    break;
                }

                currentElement = parent;
            } 
            else 
            {
                break;
            } //end of if else
        } // end of while
    } // end of removeCourse function

    
    function addNewCourseFields(defaultData)
    {
        const courseName = defaultData.name || '';
        const courseText = defaultData.text || '';
        const div = document.createElement('div');
        div.className = 'course';
        div.innerHTML = `
          <label>
            Course Name:
            <textarea name="courses[][name]" required cols="30">${courseName}</textarea>
          </label>
  
          <label>
            Course Description:
            <textarea name="courses[][description]" rows="3" cols="40" required>${courseText}</textarea>
          </label>

          <button name="removeCourse" type="button">Remove</button>
        `;
        
        courseList.appendChild(div);
        console.log(div.querySelector("button[name='removeCourse']"));
        const removeCourseButton = div.querySelector("button[name='removeCourse']");
        removeCourseButton.addEventListener('click', removeCourse);
    }

    // Add event listener for adding new course button
    addCourseButton.addEventListener('click', addNewCourseFields);

    const defaultInformation = 
    {
        fullName: 'Lauren-Kate "LK" Stewart',
        personalBackground: 'I\'ve lived in NC most of my life and right now I\'m a part time student at UNC Charlotte.',
        professionalBackground: 'Currently I work part time as a programmer/IT help for a small business and as a TA at UNC Charlotte.',
        academicBackground: 'Working on my BA in Computer Science at UNC Charlotte and am taking classes here on Transient Study since CPCC offers a lot more online classes.',
        subjectBackground: 'I\'ve taken some web and database classes at UNC Charlotte.',
        platform: 'Laptop running Windows 11.',
        funFact: 'I like collecting postage stamps, particularly ones with reptile or space themes.',
        extraInformation: 'The binomial name of the Sandfish Skink is Scincus scincus which I think is funny. They got their common name from the way they “swim” under the surface of sand.',
        imagePath: 'images/sandfish.jpg',
        imageCaption: 'I\'d rather not use a picture of myself so this is a Sandfish Skink. Source: Wilfried Berns, CC BY-SA 2.0 DE, via Wikimedia Commons '
    };
    
    // prefill default info
    populateDefault(defaultInformation);

    // Prefill course data
    const defaultCourses = 
    [
        {name: 'REL110 - World Religions', text: 'This was one of my options to fulfill some of the liberal arts credits I need and it was offered online.'},
        {name: 'WEB250 - Database Driven Websites', text: 'This class looked fun. I like web development, I like databases, and I need some elective credits.'},
        {name: 'METR 1102 - Introduction to Meteorology', text: 'I\'m taking this at UNC Charlotte to fulfill my science with lab requirement. I picked it because it looked interesting, wasn\'t chemistry or biology, and worked with my schedule.'},
        {name: 'METR 1102L - Introduction to Meteorology Lab', text: 'Required lab that goes with METR 1102.'}
    ];

    defaultCourses.forEach((course) => addNewCourseFields(course));

    // MISC

    // replace label text with selected filename when adding new car
    document.querySelector("#image").addEventListener('change', function(event)
    {
        document.querySelector("label[for=image]").innerHTML = event.target.value.split('\\').pop();
    });
  
}); // end of DOM content loaded function