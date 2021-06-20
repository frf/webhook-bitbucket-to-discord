# Webhook Bitbucket Discord

Integrator Bitbucket => Discord

#### https://webhook.app2u.co

## Register
To use you need to register

/auth/register
```json
{
    "name": "User  Name",
    "mobile_phone": "2199992222",
    "email": "email@email.com.br",
    "password": "123456"
}
```

After Register

GET: /webhooks

```json
{
    "data": [
        {
            "key": "e58b09bac772c618dad10ad84a666614",
            "webhook": "https://webhook.app2u.co/v1/webhook-message/e58b09xxxx",
            "content": "Teste app2u",
            "application": "bitbucket",
            "my_webhook": "https://discord.com/api/webhooks/8453xxx9BymKxrE2M4XVQ9OisdcAbEfcRbsgVSfjIjcEsdEZI-13lmhKhr",
            "created_at": "2021-05-21T21:29:07.000000Z"
        },
    ]
}
```

POST: /webhooks

```json
{
    "application": "bitbucket",
    "webhook": "https://discord.com/api/webhooks/854716932351721532/dasdsadsZ99BAFex6xJgEU",
    "content": "FrontEnd"
}
```

PATCH: /webhooks/<key>

```json
{
    "webhook": "https://discord.com/api/webhooks/837790871847436288/ZYdF3af7fBa9fY-sRExxxgc"
}
```

Configure bitbucket webhook

After generate your webhook exemple (https://webhook.app2u.co/v1/webhook-message/e58b09xxxx)

Use in:
![alt text](https://github.com/frf/images-apps/blob/main/discord-message/bitbucket.png?raw=true)

