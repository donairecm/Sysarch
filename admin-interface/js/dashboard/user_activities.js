      // SVG icons
      const svgIcons = {
        'Admin': `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M680-280q25 0 42.5-17.5T740-340q0-25-17.5-42.5T680-400q-25 0-42.5 17.5T620-340q0 25 17.5 42.5T680-280Zm0 120q31 0 57-14.5t42-38.5q-22-13-47-20t-52-7q-27 0-52 7t-47 20q16 24 42 38.5t57 14.5ZM480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v227q-19-8-39-14.5t-41-9.5v-147l-240-90-240 90v188q0 47 12.5 94t35 89.5Q310-290 342-254t71 60q11 32 29 61t41 52q-1 0-1.5.5t-1.5.5Zm200 0q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80ZM480-494Z"/></svg>`,
        'Inventory Manager': `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M620-163 450-333l56-56 114 114 226-226 56 56-282 282Zm220-397h-80v-200h-80v120H280v-120h-80v560h240v80H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v200ZM480-760q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z"/></svg>`,
        'Sales Manager': `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M280-640q-33 0-56.5-23.5T200-720v-80q0-33 23.5-56.5T280-880h400q33 0 56.5 23.5T760-800v80q0 33-23.5 56.5T680-640H280Zm0-80h400v-80H280v80ZM160-80q-33 0-56.5-23.5T80-160v-40h800v40q0 33-23.5 56.5T800-80H160ZM80-240l139-313q10-22 30-34.5t43-12.5h376q23 0 43 12.5t30 34.5l139 313H80Zm260-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm120 160h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Zm0-80h40q8 0 14-6t6-14q0-8-6-14t-14-6h-40q-8 0-14 6t-6 14q0 8 6 14t14 6Z"/></svg>`,
        'Supply-chain Manager': `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-160q-50 0-85-35t-35-85H40v-440q0-33 23.5-56.5T120-800h560v160h120l120 160v200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H360q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T280-280q0-17-11.5-28.5T240-320q-17 0-28.5 11.5T200-280q0 17 11.5 28.5T240-240ZM120-360h32q17-18 39-29t49-11q27 0 49 11t39 29h272v-360H120v360Zm600 120q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240Zm-40-200h170l-90-120h-80v120ZM360-540Z"/></svg>`
    };

    // Data for users and activities
    const adminNames = ["Alice", "Bob", "Charlie", "Diana", "Eve"];
    const inventoryNames = ["Frank", "George"];
    const salesNames = ["Hannah", "Isla", "Jack", "Kevin"];
    const supplyNames = ["Laura", "Michael"];
    const logs = ["Timed-in", "Timed-out", "Logged In", "Logged Out"];
    
    const activities = {
        'Admin': [
            "Reviewed Reports", "Managed User Access", "Updated Policies", "Scheduled Meetings", "Reviewed System Logs",
            "Configured Settings", "Reviewed Financials", "Handled Customer Escalations", "Checked Security", "Approved Requests",
            "Conducted Training", "Managed Inventory System", "Checked HR Portal", "Updated Employee Records", "Attended Board Meeting",
            "Prepared Annual Report", "Checked Email", "Checked Notifications", "Approved Budgets", "Assigned Tasks"
        ],
        'Inventory Manager': ["Checked Inventory Levels", "Updated Stock Information", "Reviewed Purchase Orders", "Received New Stock", "Organized Warehouse"],
        'Sales Manager': ["Completed Sale", "Updated Client Information", "Handled Customer Complaint"],
        'Supply-chain Manager': ["Updated Shipment Status", "Checked Supplier Agreement", "Reviewed Supply Forecast"]
    };

    // Generate Random Data for User Activities
    function generateRandomUserActivities() {
        const activityList = document.querySelector(".user-activities-list");

        for (let i = 0; i < 30; i++) {
            // Randomly choose a user category
            const userCategory = Object.keys(activities)[Math.floor(Math.random() * Object.keys(activities).length)];
            
            // Pick a name based on the user category
            let name;
            switch(userCategory) {
                case 'Admin': name = adminNames[Math.floor(Math.random() * adminNames.length)]; break;
                case 'Inventory Manager': name = inventoryNames[Math.floor(Math.random() * inventoryNames.length)]; break;
                case 'Sales Manager': name = salesNames[Math.floor(Math.random() * salesNames.length)]; break;
                case 'Supply-chain Manager': name = supplyNames[Math.floor(Math.random() * supplyNames.length)]; break;
            }

            // Pick a random activity or log
            const isLog = Math.random() < 0.4;
            const activityOrLog = isLog ? logs[Math.floor(Math.random() * logs.length)] : activities[userCategory][Math.floor(Math.random() * activities[userCategory].length)];

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const activityItem = document.createElement("li");
            activityItem.className = "user-activities-item";
            activityItem.innerHTML = `
                <span class="user">
                    <div class="user-icon-container">${svgIcons[userCategory]}</div>
                </span>
                <span class="name">${name}</span>
                <span class="time">${time}</span>
                <span class="activity-log ${isLog ? 'log' : 'activity'}">${activityOrLog}</span>
            `;
            
            activityList.appendChild(activityItem);
        }
    }

    // Initialize the user activities on page load
    window.onload = generateRandomUserActivities;