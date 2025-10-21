// src/services/smsService.js
import axios from 'axios'

const clientId = 'tPa6KiE12WE2vzgu3212Y63bMaxH899e'
const clientSecret = 'tPa6KiE12WE2vzgu3212Y63bMaxH899e'
const senderAddress = 'tel:+2250153676062' // Ton numéro expéditeur autorisé

const getAccessToken = async () => {
  const headers = {
    Authorization: 'Basic dFBhNktpRTEyV0UydnpndTMyMTJZNjNiTWF4SDg5OWU6UjZ6bndXU0dPcVdRenNHbExiWmVRaEJUS09TTUNXdXAwS3lqZjdWRVR1VE8=',
    'Content-Type': 'application/x-www-form-urlencoded'
  }

  const body = new URLSearchParams()
  body.append('grant_type', 'client_credentials')

  const res = await axios.post('https://api.orange.com/oauth/v3/token', body, { headers })
  return res.data.access_token
}

export const sendSMS = async (recipientPhone, message) => {
  const token = await getAccessToken()

  const headers = {
    Authorization: `Bearer ${token}`,
    'Content-Type': 'application/json'
  }

  const body = {
    outboundSMSMessageRequest: {
      address: `tel:${recipientPhone}`,
      senderAddress,
      outboundSMSTextMessage: { message }
    }
  }

  const url = `https://api.orange.com/smsmessaging/v1/outbound/${encodeURIComponent(senderAddress)}/requests`

  const res = await axios.post(url, body, { headers })
  return res.data
}
