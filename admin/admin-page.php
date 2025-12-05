<div class="wrap">
    <h1>LearnPress Bulk Export</h1>
    
    <div class="lpbe-container">
        <div class="lpbe-column lpbe-users-column">
            <h2>1. Select Users</h2>
            <div class="lpbe-search-box">
                <input type="text" id="lpbe-user-search" placeholder="Search by name or email...">
                <button type="button" id="lpbe-search-btn" class="button">Search</button>
            </div>
            <div id="lpbe-search-results" class="lpbe-list-box"></div>
            
            <h3>Selected Users</h3>
            <div id="lpbe-selected-users" class="lpbe-list-box"></div>
        </div>

        <div class="lpbe-column lpbe-courses-column">
            <h2>2. Select Courses</h2>
            <p>Select a user on the left to view their courses.</p>
            <div id="lpbe-courses-container">
                <!-- Courses will be loaded here -->
            </div>
        </div>
    </div>

    <div class="lpbe-actions">
        <button type="button" id="lpbe-export-btn" class="button button-primary button-large">Export PDF</button>
        <div id="lpbe-loading" style="display:none;">Processing...</div>
    </div>
</div>
