const express = require('express');
const app = express();
app.use(express.json());

app.get('/api/message', (req, res) => {
  res.json({ success: true, message: 'Hello from Express GET API', method: 'GET' });
});
app.post('/api/message', (req, res) => {
  const { name = 'Unknown', message = '' } = req.body || {};
  res.json({ success: true, receivedFrom: name, message, response: 'PHP called Express successfully' });
});

// Express calls PHP REST API. Apache must be running for this route.
app.get('/php-client', async (req, res) => {
  try {
  const response = await fetch('http://localhost/shopping/shopping/api/students.php');
    const data = await response.json();
    res.type('html').send('<h1>Express calling PHP REST API</h1><pre>' +
      escapeHtml(JSON.stringify(data, null, 2)) + '</pre>');
  } catch (err) {
    res.status(502).send('Could not call PHP API. Is Apache running at http://localhost/shopping? ' + escapeHtml(err.message));
  }
});
function escapeHtml(s) {
  return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
const PORT = 3000;
app.listen(PORT, () => console.log(`Express API running at http://localhost:${PORT}`));
