function createCourse() {
    const courseName = document.getElementById("name").value;
    const isDefault = document.querySelector('input[name="grading-option"]:checked').value === "default";

    fetch('course_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'create',
            course_name: courseName,
            is_default: isDefault
        })
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) location.reload();
        });
}

function deleteCourse(id) {
    if (confirm("Are you sure you want to delete this course?")) {
        fetch('course_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'delete', id })
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
    }
}

function renameCourse(id) {
    const newName = prompt("Enter the new course name:");
    if (newName) {
        fetch('course_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'rename', id, new_name: newName })
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
    }
}
 