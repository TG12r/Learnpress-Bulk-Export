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
            
            <h3>Selected Users 
                <button type="button" id="lpbe-clear-all-btn" class="button button-small" style="margin-left: 10px; display: none; vertical-align: middle;">
                    Clear All
                </button>
            </h3>
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

    <div class="lpbe-options" style="margin: 15px 0; padding: 10px; background: #f9f9f9; border-radius: 4px;">
        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <input type="checkbox" id="lpbe-include-comparison" checked>
            <strong>Include Course Comparison Table</strong>
            <span style="color: #666; font-size: 12px;">(Shows courses with 2+ students)</span>
        </label>
        <label style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" id="lpbe-debug-mode">
            <strong>Debug Mode</strong>
            <span style="color: #666; font-size: 12px;">(Show detailed logs in alert)</span>
        </label>
    </div>

    <div class="lpbe-actions">
        <button type="button" id="lpbe-export-btn" class="button button-primary button-large">Export PDF</button>
        <button type="button" id="lpbe-email-btn" class="button button-secondary button-large" style="margin-left: 10px;">Send Email to Students</button>
        <div id="lpbe-loading" style="display:none;">Processing...</div>
    </div>
</div>
