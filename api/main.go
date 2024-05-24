package main

import (
	"api/bdd"
	"database/sql"
	"encoding/json"
	"fmt"
	"log"
	"net/http"

	_ "github.com/go-sql-driver/mysql"
	"github.com/gorilla/mux"
	"github.com/jung-kurt/gofpdf"
)

var db *sql.DB

type User struct {
	ID          int     `json:"id"`
	GradeID     *int    `json:"gradeId"`
	Nom         string  `json:"nom"`
	Prenom      string  `json:"prenom"`
	Status      string  `json:"status"`
	Email       string  `json:"email"`
	Age         *string `json:"age"` // Le type est string pour refléter le type DATE
	MotDePasse  string  `json:"motDePasse"`
	Presentation *string `json:"presentation"`
}

type TypeLogement struct {
	ID   int    `json:"id"`
	Name string `json:"name"`
}

type Service struct {
	ID          int    `json:"id"`
	UserID      int    `json:"userId"`
	Description string `json:"description"`
	Type        string `json:"type"`
}

type Location struct {
	ID          int    `json:"id"`
	UserID      int    `json:"userId"`
	LogementID  int    `json:"logementId"`
	Nom         string `json:"nom"`
	Taille      int    `json:"taille"`
	Emplacement string `json:"emplacement"`
	DateDebut   string `json:"dateDebut"`
	DateFin     string `json:"dateFin"`
}

type Logement struct {
	ID                int     `json:"id"`
	UserID            int     `json:"userId"`
	Prix              float64 `json:"prix"`
	Validation        int     `json:"validation"`
	TypeConciergerie  int     `json:"typeConciergerie"`
	Adresse           string  `json:"adresse"`
	Ville             string  `json:"ville"`
	CodePostal        int     `json:"codePostal"`
	Pays              string  `json:"pays"`
	TypeBien          int     `json:"typeBien"`
	TypeLocation      int     `json:"typeLocation"`
	CapaciteLocation  int     `json:"capaciteLocation"`
	Description       string  `json:"description"`
	HeureDeContacte   string  `json:"heureDeContacte"`
	Nom               *string `json:"nom"`
}

type Calendrier struct {
	ID        int    `json:"id"`
	Date      string `json:"date"`
	ServiceID int    `json:"serviceId"`
}

type Status struct {
	ID   int    `json:"id"`
	Name string `json:"name"`
}

type Intervention struct {
	ID           int `json:"id"`
	ServiceID    int `json:"serviceId"`
	DepartementID int `json:"departementId"`
}

func getPdfLocationByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	locationID := params["id"]

	row := db.QueryRow("SELECT id, id_USER, id_LOGEMENT, nom, taille, emplacement, date_debut, date_fin FROM LOCATION WHERE id = ?", locationID)

	var location Location

	err := row.Scan(&location.ID, &location.UserID, &location.LogementID, &location.Nom, &location.Taille, &location.Emplacement, &location.DateDebut, &location.DateFin)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	// Créer un nouveau document PDF
	pdf := gofpdf.New("P", "mm", "A4", "")
	pdf.AddPage()

	// Définir la police et la taille du texte
	pdf.SetFont("Arial", "B", 16)

	// Ajouter les informations sur la location au PDF
	pdf.Cell(40, 10, fmt.Sprintf("Informations sur la location ID: %d", location.ID))
	pdf.Ln(10)
	pdf.Cell(40, 10, fmt.Sprintf("UserID: %d", location.UserID))
	pdf.Ln(10)
	pdf.Cell(40, 10, fmt.Sprintf("LogementID: %d", location.LogementID))
	pdf.Ln(10)
	pdf.Cell(40, 10, fmt.Sprintf("Nom: %s", location.Nom))
	pdf.Ln(10)
	pdf.Cell(40, 10, fmt.Sprintf("Taille: %d", location.Taille))
	pdf.Ln(10)
	pdf.Cell(40, 10, fmt.Sprintf("Emplacement: %s", location.Emplacement))
	pdf.Ln(10)
	pdf.Cell(40, 10, fmt.Sprintf("DateDebut: %s", location.DateDebut))
	pdf.Ln(10)
	pdf.Cell(40, 10, fmt.Sprintf("DateFin: %s", location.DateFin))

	// Enregistrer le document PDF dans un fichier
	err = pdf.OutputFileAndClose("location_info.pdf")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	// Envoyer le fichier PDF en réponse HTTP
	w.Header().Set("Content-Type", "application/pdf")
	http.ServeFile(w, r, "location_info.pdf")
}

func getAllStatus(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, name FROM STATUS")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var statuses []Status
	for rows.Next() {
		var status Status
		err := rows.Scan(&status.ID, &status.Name)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		statuses = append(statuses, status)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(statuses)
}

// Endpoint pour voir tous les utilisateurs
func getAllUsers(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var users []User
	for rows.Next() {
		var user User
		err := rows.Scan(&user.ID, &user.GradeID, &user.Nom, &user.Prenom, &user.Status, &user.Email, &user.Age, &user.MotDePasse, &user.Presentation)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		users = append(users, user)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(users)
}

func getUserByID(w http.ResponseWriter, r *http.Request) {
	// Récupérer l'ID de l'utilisateur depuis les paramètres de la requête
	params := mux.Vars(r)
	userID := params["id"]

	// Préparer la requête SQL pour récupérer l'utilisateur par son ID
	row := db.QueryRow("SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER WHERE id = ?", userID)

	// Créer une variable pour stocker les données de l'utilisateur
	var user User

	// Scanner les données de l'utilisateur dans la variable user
	err := row.Scan(&user.ID, &user.GradeID, &user.Nom, &user.Prenom, &user.Status, &user.Email, &user.Age, &user.MotDePasse, &user.Presentation)
	if err != nil {
		// Si une erreur se produit lors de la récupération de l'utilisateur, renvoyer une erreur au client
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	// Encoder les données de l'utilisateur en JSON et les renvoyer en réponse
	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(user)
}

func addUser(w http.ResponseWriter, r *http.Request) {
    var user User
    // Décodez le corps de la demande JSON dans la structure User
    err := json.NewDecoder(r.Body).Decode(&user)
    if err != nil {
        http.Error(w, err.Error(), http.StatusBadRequest)
        return
    }

    // Insérez l'utilisateur dans la base de données
    _, err = db.Exec("INSERT INTO USER (id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        user.GradeID, user.Nom, user.Prenom, user.Status, user.Email, user.Age, user.MotDePasse, user.Presentation)
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    // Renvoyer une réponse 201 Created
    w.WriteHeader(http.StatusCreated)
}

func addService(w http.ResponseWriter, r *http.Request) {
    var service Service
    // Décodez le corps de la demande JSON dans la structure Service
    err := json.NewDecoder(r.Body).Decode(&service)
    if err != nil {
        http.Error(w, err.Error(), http.StatusBadRequest)
        return
    }

    // Insérez le service dans la base de données
    _, err = db.Exec("INSERT INTO SERVICE (id_USER, description, type) VALUES (?, ?, ?)",
        service.UserID, service.Description, service.Type)
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    // Renvoyer une réponse 201 Created
    w.WriteHeader(http.StatusCreated)
}

func addLogement(w http.ResponseWriter, r *http.Request) {
    var logement Logement
    // Décodez le corps de la demande JSON dans la structure Logement
    err := json.NewDecoder(r.Body).Decode(&logement)
    if err != nil {
        http.Error(w, err.Error(), http.StatusBadRequest)
        return
    }

    // Insérez le logement dans la base de données
    _, err = db.Exec("INSERT INTO LOGEMENT (id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        logement.UserID, logement.Prix, logement.Validation, logement.TypeConciergerie, logement.Adresse, logement.Ville, logement.CodePostal, logement.Pays, logement.TypeBien, logement.TypeLocation, logement.CapaciteLocation, logement.Description, logement.HeureDeContacte, logement.Nom)
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    // Renvoyer une réponse 201 Created
    w.WriteHeader(http.StatusCreated)
}


func getAllServices(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_USER, description, type FROM SERVICE")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var services []Service
	for rows.Next() {
		var service Service
		err := rows.Scan(&service.ID, &service.UserID, &service.Description, &service.Type)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		services = append(services, service)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(services)
}

// Endpoint pour voir toutes les réservations
func getAllLocations(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_USER, id_LOGEMENT, nom, taille, emplacement, date_debut, date_fin FROM LOCATION")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var locations []Location
	for rows.Next() {
		var location Location
		err := rows.Scan(&location.ID, &location.UserID, &location.LogementID, &location.Nom, &location.Taille, &location.Emplacement, &location.DateDebut, &location.DateFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
			locations = append(locations, location)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(locations)
}

// Endpoint pour voir tous les logements
func getAllLogements(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom FROM LOGEMENT")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var logements []Logement
	for rows.Next() {
		var logement Logement
		err := rows.Scan(&logement.ID, &logement.UserID, &logement.Prix, &logement.Validation, &logement.TypeConciergerie, &logement.Adresse, &logement.Ville, &logement.CodePostal, &logement.Pays, &logement.TypeBien, &logement.TypeLocation, &logement.CapaciteLocation, &logement.Description, &logement.HeureDeContacte, &logement.Nom)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		logements = append(logements, logement)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(logements)
}

func getAllCalendrier(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, date, id_SERVICE FROM CALENDRIER")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var calendrier []Calendrier
	for rows.Next() {
		var c Calendrier
		err := rows.Scan(&c.ID, &c.Date, &c.ServiceID)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		calendrier = append(calendrier, c)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(calendrier)
}

func getServiceByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	serviceID := params["id"]

	row := db.QueryRow("SELECT id, id_USER, description, type FROM SERVICE WHERE id = ?", serviceID)

	var service Service

	err := row.Scan(&service.ID, &service.UserID, &service.Description, &service.Type)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(service)
}

func getLocationByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	locationID := params["id"]

	row := db.QueryRow("SELECT id, id_USER, id_LOGEMENT, nom, taille, emplacement, date_debut, date_fin FROM LOCATION WHERE id = ?", locationID)

	var location Location

	err := row.Scan(&location.ID, &location.UserID, &location.LogementID, &location.Nom, &location.Taille, &location.Emplacement, &location.DateDebut, &location.DateFin)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(location)
}

func getLogementByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	logementID := params["id"]

	row := db.QueryRow("SELECT id, id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom FROM LOGEMENT WHERE id = ?", logementID)

	var logement Logement

	err := row.Scan(&logement.ID, &logement.UserID, &logement.Prix, &logement.Validation, &logement.TypeConciergerie, &logement.Adresse, &logement.Ville, &logement.CodePostal, &logement.Pays, &logement.TypeBien, &logement.TypeLocation, &logement.CapaciteLocation, &logement.Description, &logement.HeureDeContacte, &logement.Nom)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(logement)
}

func getAllTypeLogement(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, name FROM TYPE_LOGEMENT")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var typesLogement []TypeLogement
	for rows.Next() {
		var typeLogement TypeLogement
		err := rows.Scan(&typeLogement.ID, &typeLogement.Name)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		typesLogement = append(typesLogement, typeLogement)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(typesLogement)
}

func getStatusByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	statusID := params["id"]

	row := db.QueryRow("SELECT id, name FROM STATUS WHERE id = ?", statusID)

	var status Status
	err := row.Scan(&status.ID, &status.Name)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(status)
}

func getAllInterventions(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_service, id_departement FROM INTERVENTION")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var interventions []Intervention
	for rows.Next() {
		var intervention Intervention
		err := rows.Scan(&intervention.ID, &intervention.ServiceID, &intervention.DepartementID)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		interventions = append(interventions, intervention)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(interventions)
}

func main() {
	var err error
	db, err = bdd.ConnectDB()
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	r := mux.NewRouter()

	r.HandleFunc("/users", getAllUsers).Methods("GET")
	r.HandleFunc("/services", getAllServices).Methods("GET")
	r.HandleFunc("/locations", getAllLocations).Methods("GET")
	r.HandleFunc("/logements", getAllLogements).Methods("GET")
	r.HandleFunc("/calendrier", getAllCalendrier).Methods("GET")
	r.HandleFunc("/users/{id}", getUserByID).Methods("GET")
	r.HandleFunc("/services/{id}", getServiceByID).Methods("GET")
	r.HandleFunc("/locations/{id}", getLocationByID).Methods("GET")
	r.HandleFunc("/logements/{id}", getLogementByID).Methods("GET")
	r.HandleFunc("/pdflocations/{id}", getPdfLocationByID).Methods("GET")
	r.HandleFunc("/typelogement", getAllTypeLogement).Methods("GET")
	r.HandleFunc("/status", getAllStatus).Methods("GET")
	r.HandleFunc("/status/{id}", getStatusByID).Methods("GET")
	r.HandleFunc("/interventions", getAllInterventions).Methods("GET")
	r.HandleFunc("/users", addUser).Methods("POST")
	r.HandleFunc("/services", addService).Methods("POST")
	r.HandleFunc("/logements", addLogement).Methods("POST")


	log.Fatal(http.ListenAndServe(":8080", r))
}
