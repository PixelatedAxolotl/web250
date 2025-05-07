<?php
$introductionInformation = 
[
    'fullName'               => !empty($_REQUEST['fullName'])                ? htmlspecialchars($_REQUEST['fullName'])                : '',
    'personalBackground'     => !empty($_REQUEST['personalBackground'])      ? htmlspecialchars($_REQUEST['personalBackground'])      : '',
    'professionalBackground' => !empty($_REQUEST['professionalBackground'])  ? htmlspecialchars($_REQUEST['professionalBackground'])  : '',
    'academicBackground'     => !empty($_REQUEST['academicBackground'])      ? htmlspecialchars($_REQUEST['academicBackground'])      : '',
    'subjectBackground'      => !empty($_REQUEST['subjectBackground'])       ? htmlspecialchars($_REQUEST['subjectBackground'])       : '',
    'platform'               => !empty($_REQUEST['platform'])                ? htmlspecialchars($_REQUEST['platform'])                : '',
    'funFact'                => !empty($_REQUEST['funFact'])                 ? htmlspecialchars($_REQUEST['funFact'])                 : '',
    'extraInformation'       => !empty($_REQUEST['extraInformation'])        ? htmlspecialchars($_REQUEST['extraInformation'])        : '',
    'courses'                => isset($_REQUEST['courses'])                  ? $_REQUEST['courses']                                   : [],
    'imagePath'              => "images/sandfish.jpg",
    'imageCaption'           => !empty($_REQUEST['imageCaption'])            ? htmlspecialchars($_REQUEST['imageCaption'])            : ''
];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        //move image to images folder and set the path if uploaded
        if (!empty($_FILES['imageFile']) && $_FILES['imageFile']['error'] === 0) 
        {
            $uploadPath = 'images/' . basename($_FILES['imageFile']['name']);
            move_uploaded_file($_FILES['imageFile']['tmp_name'], $uploadPath);
            $introductionInformation['imagePath'] = $uploadPath;
        }
    }
?>





<h3><?php echo $introductionInformation['fullName'] ?></h3>

<figure>
    <img src="<?php echo htmlspecialchars($introductionInformation['imagePath']); ?>" alt="Introduction image">
    <figcaption>
        <em>I'd rather not use a picture of myself so this is a Sandfish Skink.</em><br>
        Source:
            <a href="https://commons.wikimedia.org/wiki/File:Apothekerskink01.jpg">Wilfried Berns</a>,
            <a href="https://creativecommons.org/licenses/by-sa/2.0/de/deed.en">CC BY-SA 2.0 DE</a>, 
            via Wikimedia Commons 
    </figcaption>
</figure>
<ul>
    <li>
        <strong>Personal Background:</strong>
        <?php echo $introductionInformation['personalBackground'] ?>

    </li>
    <li>
        <strong>Professional Background:</strong>
        <?php echo $introductionInformation['professionalBackground'] ?>

    </li>
    <li>
        <strong>Academic Background:</strong>
        <?php echo $introductionInformation['academicBackground'] ?>

    </li>
    <li>
        <strong>Background in this Subject:</strong>
        <?php echo $introductionInformation['subjectBackground'] ?>

    </li>
    <li>
        <strong>Primary Computer Platform:</strong>
        <?php echo $introductionInformation['platform'] ?>

    </li>
    <li>
        <strong>Courses I'm Taking &amp; Why:</strong>

        <ul>
            <?php
                foreach (array_chunk($introductionInformation['courses'], 2) as $course)
                {
                    $name = $course[0]['name'] ?? '';
                    $text = $course[1]['description'] ?? '';
                    echo "
                        <li>
                            <strong>$name:</strong> 
                            $text
                        </li>";
                }
            ?>
        </ul>
    </li>
    <li>
        <strong>Funny/Interesting Item to Remember me by:</strong>
        <?php echo $introductionInformation['funFact'] ?>
    </li>
    <li>
        <strong>I'd also like to Share:</strong>
        <?php echo $introductionInformation['extraInformation'] ?>
    </li>
</ul>