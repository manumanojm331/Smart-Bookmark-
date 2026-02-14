document.addEventListener('DOMContentLoaded', function() {
    const bookmarksContainer = document.getElementById('bookmarksContainer');
    const bookmarkForm = document.getElementById('bookmarkForm');
    
    // Load bookmarks immediately
    loadBookmarks();
    
    // Set up real-time updates (polling every 2 seconds)
    setInterval(loadBookmarks, 2000);
    
    // Handle form submission
    if (bookmarkForm) {
        bookmarkForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const title = document.getElementById('title').value;
            const url = document.getElementById('url').value;
            
            try {
                const response = await fetch('api/add-bookmark.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ title, url })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    bookmarkForm.reset();
                    await loadBookmarks(); // Immediate refresh
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add bookmark');
            }
        });
    }
    
    // Function to load bookmarks
    async function loadBookmarks() {
        try {
            const response = await fetch('api/get-bookmarks.php');
            const bookmarks = await response.json();
            
            displayBookmarks(bookmarks);
        } catch (error) {
            console.error('Error loading bookmarks:', error);
            bookmarksContainer.innerHTML = '<p class="error">Failed to load bookmarks</p>';
        }
    }
    
    // Function to display bookmarks
    function displayBookmarks(bookmarks) {
        if (!bookmarksContainer) return;
        
        if (bookmarks.length === 0) {
            bookmarksContainer.innerHTML = '<p class="no-bookmarks">No bookmarks yet. Add your first bookmark above!</p>';
            return;
        }
        
        let html = '';
        bookmarks.forEach(bookmark => {
            html += `
                <div class="bookmark-item" data-id="${bookmark.id}">
                    <div class="bookmark-content">
                        <h3><a href="${bookmark.url}" target="_blank" rel="noopener noreferrer">${escapeHtml(bookmark.title)}</a></h3>
                        <p class="bookmark-url">${escapeHtml(bookmark.url)}</p>
                        <small>Added: ${new Date(bookmark.created_at).toLocaleString()}</small>
                    </div>
                    <button class="btn-delete" onclick="deleteBookmark(${bookmark.id})">Delete</button>
                </div>
            `;
        });
        
        bookmarksContainer.innerHTML = html;
    }
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});

// Global delete function
async function deleteBookmark(bookmarkId) {
    if (!confirm('Are you sure you want to delete this bookmark?')) {
        return;
    }
    
    try {
        const response = await fetch('api/delete-bookmark.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ bookmark_id: bookmarkId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Remove from DOM immediately
            const bookmarkElement = document.querySelector(`.bookmark-item[data-id="${bookmarkId}"]`);
            if (bookmarkElement) {
                bookmarkElement.remove();
            }
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete bookmark');
    }
}
