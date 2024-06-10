const PDFDocument = require('pdfkit');
const nodemailer = require('nodemailer');
const fs = require('fs');

// Set the content of the PDF
const questions = [
    'Did we treat you with dignity and respect?', 
    'Were you involved as much as you wanted to be in your care?', 
    'Did our staff ask for your consent in all that they did with you?',
    'Did we provide you with information to enable you make informed decisions about what was being proposed to you during our engagement?', 
    'Did you feel supported by our staff?',
    'Did we treat you with kindness and compassion?', 
    'Did you feel listened to?', 
    'Did you have the opportunity to ask questions?',  
    'Overall Service Rating', 
    'What did we do well?', 
    'What do we need to change to make the experience better and how do we need to change it?'
];

const responses = [
    process.env.q1 || 'No response',
    process.env.q2 || 'No response',
    process.env.q3 || 'No response',
    process.env.q4 || 'No response',
    process.env.q5 || 'No response',
    process.env.q6 || 'No response',
    process.env.q7 || 'No response',
    process.env.q8 || 'No response',
    process.env.overall_rating || 'No response',
    process.env.feedback_good || 'No response',
    process.env.feedback_improvement || 'No response'
];

const content = questions.map((question, index) => `${question}\nResponse: ${responses[index]}\n\n`).join('');

// Create PDF
const pdfPath = `/Users/ejenamvictor/tmp_pdf_storage/feedback_${Date.now()}.pdf`;
const doc = new PDFDocument();
doc.pipe(fs.createWriteStream(pdfPath));
doc.fontSize(12).text(content);
doc.end();

// Configure nodemailer
const transporter = nodemailer.createTransport({
    host: 'smtp.gmail.com',
    port: 587,
    secure: false,
    auth: {
        user: 'ejenamvictor76@gmail.com',
        pass: 'dnsladphvqaxqiyp'
    }
});

// Send email with PDF attachment
const mailOptions = {
    from: 'ejenamvictor76@gmail.com',
    to: 'recipient@example.com',
    subject: 'New Feedback Submission',
    html: 'A new feedback form has been submitted. Please find the attached PDF.',
    attachments: [
        {
            filename: `feedback_${Date.now()}.pdf`,
            path: pdfPath
        }
    ]
};

transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
        console.error('Error:', error);
    } else {
        console.log('Email sent:', info.response);
    }
});
