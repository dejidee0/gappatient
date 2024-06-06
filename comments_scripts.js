document.getElementById('viewComments').addEventListener('click', function() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("commentsSection").innerHTML = this.responseText;
            document.getElementById("commentsSection").style.display = "block";
        }
    };
    xhttp.open("GET", "load_comments.php", true);
    xhttp.send();
});

function loadComments() {
    var commentsSection = document.getElementById('commentsSection');
    if (commentsSection.style.display === 'block') {
        commentsSection.style.display = 'none';
    } else {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://localhost:8080/load_comments.php', true);
        xhr.onload = function() {
            if (this.status === 200) {
                commentsSection.innerHTML = this.responseText;
                commentsSection.style.display = 'block';
            } else {
                console.error('Error fetching comments:', this.status, this.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.send();
    }
}

document.getElementById('comment-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if(data.success) {
            clearForm(form);
            displaySuccessMessage('Comment successfully saved. Message has been sent.');
        } else {
            alert(data.message || 'An error occurred');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

function clearForm(form) {
    form.reset(); // Resets all form fields
    grecaptcha.reset(); // Reset reCAPTCHA
}

function displaySuccessMessage(message) {
    const successMessage = document.getElementById('successMessage');
    successMessage.textContent = message;
    successMessage.style.display = 'block';
    setTimeout(() => {
        successMessage.style.display = 'none';
    }, 4000);
}

