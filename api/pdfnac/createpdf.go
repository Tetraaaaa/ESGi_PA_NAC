package pdfnac

import (
	"api/apinac"
	"database/sql"
	"fmt"
	"net/http"

	"github.com/gorilla/mux"
	"github.com/jung-kurt/gofpdf"
)

var db *sql.DB

func SetDB(database *sql.DB) {
	db = database
}

func GetPdfLocationByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	locationID := params["id"]

	row := db.QueryRow("SELECT id, id_USER, id_LOGEMENT,  date_debut, date_fin FROM LOCATION WHERE id = ?", locationID)

	var location apinac.Location

	err := row.Scan(&location.ID, &location.UserID, &location.LogementID, &location.DateDebut, &location.DateFin)
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