// backend/index.js
const express = require('express');
const cors = require('cors');
const axios = require('axios');

const app = express();
app.use(cors());
app.use(express.json());

const WAVE_API_KEY = 'wave_ci_prod_tZ_KPHYpy0-XZfMMQGRXrHP02DW3rmCuupyGxELJAFXSGm2I9lFAjNY1VoJTsYZH2xkbDm3NFc54zRCMhm3fAjoS-Wnh-MwZrw'; // remplace par ta clé API
const WAVE_API_URL = 'https://api.wave.com/v1/checkout/sessions';

app.post('/api/payer', async (req, res) => {
  const { amount, currency, email, phone, description } = req.body;

  try {
    const response = await axios.post(
      WAVE_API_URL,
      {
        amount: {
          value: amount,
          currency: currency,
        },
        customer: {
          email: email,
          phone_number: phone,
        },
        description: description,
        callback_url: 'https://votresite.com/callback',
        success_url: 'https://votresite.com/success',
        cancel_url: 'https://votresite.com/cancel',
      },
      {
        headers: {
          Authorization: `Bearer ${WAVE_API_KEY}`,
          'Content-Type': 'application/json',
        },
      }
    );

    res.json({ checkout_url: response.data.checkout_url });
  } catch (error) {
    console.error('Erreur Wave API:', error.response?.data || error.message);
    res.status(500).json({ message: 'Erreur lors de la création de la session de paiement' });
  }
});

app.listen(3000, () => {
  console.log('API backend Wave en écoute sur http://localhost:3000');
});
