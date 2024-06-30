# Étape de build
FROM golang:1.20 as builder

WORKDIR /app

# Copier le module go et les fichiers go mod
COPY go.mod go.sum ./
RUN go mod download

# Copier le code source de l'application
COPY . .

# Copier le fichier .env
COPY .env .

# Construire l'exécutable
RUN CGO_ENABLED=0 GOOS=linux go build -o /app/myapp

# Étape de production
FROM alpine:latest

WORKDIR /root/

# Copier l'exécutable depuis l'étape de build
COPY --from=builder /app/myapp .
COPY --from=builder /app/.env .

# Exposer le port de l'application (modifiez selon vos besoins)
EXPOSE 8080

# Commande pour exécuter l'application
CMD ["./myapp"]