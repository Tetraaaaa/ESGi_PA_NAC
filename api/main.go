package main

import (
	"api/apinac"
	"api/bdd"
	"api/pdfnac"
	"database/sql"
	"log"
	"net/http"

	_ "github.com/go-sql-driver/mysql"
	"github.com/gorilla/mux"
)

var db *sql.DB

func main() {
	var err error
	db, err = bdd.ConnectDB()
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()
	apinac.SetDB(db)
	pdfnac.SetDB(db)

	r := mux.NewRouter()

	// Users
	r.HandleFunc("/users", apinac.GetAllUsers).Methods("GET")
	// id
	// nom
	// prenom
	// status
	// email
	// age
	// motDePasse
	// presentation
	r.HandleFunc("/users/id/{id}", apinac.GetUserByID).Methods("GET")
	r.HandleFunc("/users/email", apinac.GetAllEmailUser).Methods("GET")
	r.HandleFunc("/users/email/{email}", apinac.GetUserByEmail).Methods("GET")
	r.HandleFunc("/users/research-email/{email}", apinac.GetUserByResearchEmail).Methods("GET")
	r.HandleFunc("/users/prenom/{prenom}", apinac.GetUserByPrenom).Methods("GET")
	r.HandleFunc("/users/status/{status}", apinac.GetUserByStatus).Methods("GET")
	r.HandleFunc("/users", apinac.AddUser).Methods("POST")
	r.HandleFunc("/users/verif/connec", apinac.AuthenticateUser).Methods("POST")

	// Services
	r.HandleFunc("/services", apinac.GetAllServices).Methods("GET")
	r.HandleFunc("/services/id/{id}", apinac.GetServiceByID).Methods("GET")
	r.HandleFunc("/services", apinac.AddService).Methods("POST")

	// Locations
	r.HandleFunc("/locations", apinac.GetAllLocations).Methods("GET")
	r.HandleFunc("/locations/id/{id}", apinac.GetLocationByID).Methods("GET")

	// Logements
	r.HandleFunc("/logements", apinac.GetAllLogements).Methods("GET")
	r.HandleFunc("/logements/id/{id}", apinac.GetLogementByID).Methods("GET")
	r.HandleFunc("/typelogement", apinac.GetAllTypeLogement).Methods("GET")
	r.HandleFunc("/logements", apinac.AddLogement).Methods("POST")

	// Calendrier
	r.HandleFunc("/calendrier", apinac.GetAllCalendrier).Methods("GET")

	// Status
	r.HandleFunc("/status", apinac.GetAllStatus).Methods("GET")
	r.HandleFunc("/status/id/{id}", apinac.GetStatusByID).Methods("GET")

	// Interventions
	r.HandleFunc("/interventions", apinac.GetAllInterventions).Methods("GET")

	// PDF
	r.HandleFunc("/pdflocations/{id}", pdfnac.GetPdfLocationByID).Methods("GET")

	log.Println("Starting server on :8080")
    err = http.ListenAndServeTLS(":8080", "/etc/letsencrypt/live/api.nac.ovh/fullchain.pem", "/etc/letsencrypt/live/api.nac.ovh/privkey.pem", r)
    if err != nil {
        log.Fatalf("Failed to start server: %v", err)
    }
}
