package apinac

import (
	"encoding/json"
	"net/http"

	"github.com/gorilla/mux"
)

type Logement struct {
	ID                int     `json:"id"`
	UserID            int     `json:"userId"`
	Prix              float64 `json:"prix"`
	Validation        *int     `json:"validation"`
	TypeConciergerie  *int     `json:"typeConciergerie"`
	Adresse           string  `json:"adresse"`
	Ville             string  `json:"ville"`
	CodePostal        int     `json:"codePostal"`
	Pays              string  `json:"pays"`
	TypeBien          int     `json:"typeBien"`
	TypeLocation      *int     `json:"typeLocation"`
	CapaciteLocation  int     `json:"capaciteLocation"`
	Description       string  `json:"description"`
	HeureDeContacte   string  `json:"heureDeContacte"`
	Nom               *string `json:"nom"`
}

type TypeLogement struct {
	ID   int    `json:"id"`
	Name string `json:"name"`
}

type PhotoLogement struct {
    ID         int    `json:"id"`
    LogementID int    `json:"logementId"`
    Name       string `json:"name"`
    Largeur    int    `json:"largeur"`
    Hauteur    int    `json:"hauteur"`
    Emplacement string `json:"emplacement"`
}

func GetLogementByID(w http.ResponseWriter, r *http.Request) {
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

func GetLogementByPays(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	logementPays := params["pays"]

	rows, err := db.Query("SELECT id, id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom FROM LOGEMENT WHERE pays = ?", logementPays)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var logements []Logement

	for rows.Next() {
		var logement Logement
		err = rows.Scan(&logement.ID, &logement.UserID, &logement.Prix, &logement.Validation, &logement.TypeConciergerie, &logement.Adresse, &logement.Ville, &logement.CodePostal, &logement.Pays, &logement.TypeBien, &logement.TypeLocation, &logement.CapaciteLocation, &logement.Description, &logement.HeureDeContacte, &logement.Nom)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		logements = append(logements, logement)
	}

	if err = rows.Err(); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	if err = json.NewEncoder(w).Encode(logements); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
}

func GetLogementByVille(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	logementVille := params["ville"]

	rows, err := db.Query("SELECT id, id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom FROM LOGEMENT WHERE ville = ?", logementVille)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var logements []Logement

	for rows.Next() {
		var logement Logement
		err = rows.Scan(&logement.ID, &logement.UserID, &logement.Prix, &logement.Validation, &logement.TypeConciergerie, &logement.Adresse, &logement.Ville, &logement.CodePostal, &logement.Pays, &logement.TypeBien, &logement.TypeLocation, &logement.CapaciteLocation, &logement.Description, &logement.HeureDeContacte, &logement.Nom)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		logements = append(logements, logement)
	}

	if err = rows.Err(); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	if err = json.NewEncoder(w).Encode(logements); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
}
func GetLogementByType(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	logementType := params["type"]

	rows, err := db.Query("SELECT id, id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom FROM LOGEMENT WHERE type_bien = ?", logementType)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var logements []Logement

	for rows.Next() {
		var logement Logement
		err = rows.Scan(&logement.ID, &logement.UserID, &logement.Prix, &logement.Validation, &logement.TypeConciergerie, &logement.Adresse, &logement.Ville, &logement.CodePostal, &logement.Pays, &logement.TypeBien, &logement.TypeLocation, &logement.CapaciteLocation, &logement.Description, &logement.HeureDeContacte, &logement.Nom)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		logements = append(logements, logement)
	}

	if err = rows.Err(); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	if err = json.NewEncoder(w).Encode(logements); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
}

func GetLogementByCapacite(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	minCapacite := params["min_capacite"]
	maxCapacite := params["max_capacite"]

	rows, err := db.Query("SELECT id, id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom FROM LOGEMENT WHERE capacite_location BETWEEN ? AND ?", minCapacite, maxCapacite)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var logements []Logement

	for rows.Next() {
		var logement Logement
		err = rows.Scan(&logement.ID, &logement.UserID, &logement.Prix, &logement.Validation, &logement.TypeConciergerie, &logement.Adresse, &logement.Ville, &logement.CodePostal, &logement.Pays, &logement.TypeBien, &logement.TypeLocation, &logement.CapaciteLocation, &logement.Description, &logement.HeureDeContacte, &logement.Nom)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		logements = append(logements, logement)
	}

	// Check if there was an error after the loop
	if err = rows.Err(); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	if err = json.NewEncoder(w).Encode(logements); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
}


func GetLogementByCodePostal(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	logementCodePostal := params["code_postal"]

	rows, err := db.Query("SELECT id, id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte, nom FROM LOGEMENT WHERE code_postal = ?", logementCodePostal)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var logements []Logement

	for rows.Next() {
		var logement Logement
		err = rows.Scan(&logement.ID, &logement.UserID, &logement.Prix, &logement.Validation, &logement.TypeConciergerie, &logement.Adresse, &logement.Ville, &logement.CodePostal, &logement.Pays, &logement.TypeBien, &logement.TypeLocation, &logement.CapaciteLocation, &logement.Description, &logement.HeureDeContacte, &logement.Nom)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		logements = append(logements, logement)
	}

	// Check if there was an error after the loop
	if err = rows.Err(); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	if err = json.NewEncoder(w).Encode(logements); err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
}


func AddLogement(w http.ResponseWriter, r *http.Request) {
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

func GetAllLogements(w http.ResponseWriter, r *http.Request) {
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

func GetAllTypeLogement(w http.ResponseWriter, r *http.Request) {
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

func GetTypeLogementByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	typeLogementID := params["id"]

	row := db.QueryRow("SELECT id, name FROM TYPE_LOGEMENT WHERE id = ?", typeLogementID)

	var typeLogement TypeLogement

	err := row.Scan(&typeLogement.ID, &typeLogement.Name)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(typeLogement)
}

func GetAllPhotosLogement(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_Logement, nom, largeur, hauteur, emplacement FROM PHOTO_LOGEMENT")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var photos []PhotoLogement
	for rows.Next() {
		var photo PhotoLogement
		err := rows.Scan(&photo.ID, &photo.LogementID, &photo.Name, &photo.Largeur, &photo.Hauteur, &photo.Emplacement)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		photos = append(photos, photo)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(photos)
}

func GetPhotosByLogementID(w http.ResponseWriter, r *http.Request) {
    params := mux.Vars(r)
    logementID := params["id"]

    rows, err := db.Query("SELECT id, id_Logement, nom, largeur, hauteur, emplacement FROM PHOTO_LOGEMENT WHERE id_Logement = ?", logementID)
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }
    defer rows.Close()

    var photos []PhotoLogement
    for rows.Next() {
        var photo PhotoLogement
        err := rows.Scan(&photo.ID, &photo.LogementID, &photo.Name, &photo.Largeur, &photo.Hauteur, &photo.Emplacement)
        if err != nil {
            http.Error(w, err.Error(), http.StatusInternalServerError)
            return
        }
        photos = append(photos, photo)
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(photos)
}
