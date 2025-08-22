async function getTemplate(templateName, data = null, elementID) {
    try {
        const res = await fetch(`/template/${templateName}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const resHtml = await res.text();

        if (!res.ok) {
            toastr.warning(resHtml);
            return;
        }

        document.getElementById(elementID).insertAdjacentHTML('beforeend', resHtml);
    }
    catch (err) {
        toastr.error('Failed fetching template "' + templateName + '"');
        console.error('Failed fetching template "' + templateName + '"', err.message);
    }
}