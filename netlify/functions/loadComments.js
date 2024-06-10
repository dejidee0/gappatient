const mysql = require('mysql2/promise');

exports.handler = async (event, context) => {
    // Database credentials
    const connectionConfig = {
        host: "sql109.infinityfree.com",
        user: "if0_36548773",
        password: "mEvo5LBUfd",
        database: "if0_36548773_gaprecruitment"
    };

    // Create connection
    let connection;
    try {
        connection = await mysql.createConnection(connectionConfig);
    } catch (error) {
        return {
            statusCode: 500,
            body: `Database connection failed: ${error.message}`
        };
    }

    try {
        // Select comment, user_type, and created_at from the comments table
        const [rows, fields] = await connection.execute("SELECT comment, user_type, created_at FROM comments ORDER BY created_at DESC");

        if (rows.length > 0) {
            const commentsList = rows.map(row => {
                const comment = row.comment ? row.comment : '';
                const userType = row.user_type ? row.user_type : 'Anonymous';
                const createdAt = row.created_at ? row.created_at : 'Unknown date';
                return `<p>${comment}</p><p>Posted by: ${userType} on ${createdAt}</p>`;
            }).join('');

            return {
                statusCode: 200,
                body: `<div class="comments-list">${commentsList}</div>`
            };
        } else {
            return {
                statusCode: 200,
                body: "No comments yet."
            };
        }
    } catch (error) {
        return {
            statusCode: 500,
            body: `Error fetching comments: ${error.message}`
        };
    } finally {
        await connection.end();
    }
};
