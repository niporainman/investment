$(document).on("submit", "form[data-url]", "#theForm", function(e) {
    e.preventDefault();

    let $form = $(this);
    let formData = new FormData(this);
    let actionUrl = $form.data("url"); // Get URL from form

    $.ajax({
        url: actionUrl,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {
            $("#formMessage").html(`
                <div class="alert alert-info mb-0" role="alert">
                    Submitting, please wait...
                </div>
            `);
        },
        success: function(response) {
            // Process UI updates if provided
            if (response.updates) {
                for (let selector in response.updates) {
                    let update = response.updates[selector];

                    // If plain text, update text content
                    if (typeof update === "string") {
                        $(selector).text(update);
                    }
                    // If object, update attributes
                    else if (typeof update === "object") {
                        for (let attr in update) {
                            $(selector).attr(attr, update[attr]);
                        }
                    }
                }
            }

            // Success handling
            if (response.status === "success") {
                $("#formMessage").html(`
                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                $form[0].reset();
            } 
            // Error handling from server
            else {
                $("#formMessage").html(`
                    <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.log("AJAX error response:", xhr.responseText);
            $("#formMessage").html(`
                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                    Something went wrong. Please try again.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        }
    });
});