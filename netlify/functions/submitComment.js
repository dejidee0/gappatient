const fetch = require('node-fetch');
const nodemailer = require('nodemailer');
const mysql = require('mysql2/promise');

exports.handler = async (event, context) => {
    if (event.httpMethod !== 'POST') {
        return {
            statusCode: 405,
            body: 'Method Not Allowed',
        };
    }

    const { user_type, comment, email, 'g-recaptcha-response': captcha } = JSON.parse(event.body);

    if (!user_type || !comment || !email || !captcha) {
        return {
            statusCode: 400,
            body: 'Bad Request: Missing required fields',
        };
    }

    // Verify CAPTCHA
    const secretKey = '6LeTY_UpAAAAAKtWxoGesEALQeLhl_nTaJBBH_Hy';
    const captchaVerificationURL = `https://www.google.com/recaptcha/api/siteverify?secret=${secretKey}&response=${captcha}`;

    const response = await fetch(captchaVerificationURL, { method: 'POST' });
    const responseBody = await response.json();

    if (!responseBody.success) {
        return {
            statusCode: 400,
            body: 'CAPTCHA verification failed. Please try again.',
        };
    }

    // Database credentials and connection
    const connectionConfig = {
        host: 'localhost',
        user: 'root',
        password: 'MosesTakunda3@',
        database: 'gaptransport',
    };

    let connection;
    try {
        connection = await mysql.createConnection(connectionConfig);
    } catch (error) {
        return {
            statusCode: 500,
            body: `Database connection failed: ${error.message}`,
        };
    }

    try {
        const [result] = await connection.execute(
            'INSERT INTO comments (comment, email, user_type) VALUES (?, ?, ?)',
            [comment, email, user_type]
        );

        // Send email notification
        let transporter = nodemailer.createTransport({
            host: 'smtp.gmail.com',
            port: 587,
            secure: false,
            auth: {
                user: 'ejenamvictor76@gmail.com',
                pass: 'dnsladphvqaxqiyp',
            },
        });

        let mailOptions = {
            from: 'ejenamvictor76@gmail.com',
            to: email,
            subject: 'New Comment Posted',
            text: `A new comment has been posted: ${comment}`,
            html: `<b>A new comment has been posted:</b> ${comment}`,
        };

        await transporter.sendMail(mailOptions);

        return {
            statusCode: 200,
            body: JSON.stringify({ message: 'Comment successfully saved and email sent.' }),
        };
    } catch (error) {
        return {
            statusCode: 500,
            body: `Error saving comment or sending email: ${error.message}`,
        };
    } finally {
        await connection.end();
    }
};
