package apinac

import (
	"encoding/json"
	"net/http"
	"time"

	"github.com/gorilla/mux"
)

type Grade struct {
	ID        int    `json:"id"`
	ID_Grade  int    `json:"idGrade"`
	ID_User   int    `json:"idUser"`
	DateDeFin string `json:"date"`
}

type TypeGrade struct {
	ID          int     `json:"id"`
	Name        string  `json:"name"`
	PrixMois    float32 `json:"prix_mois"`
	PrixAn      float32 `json:"prix_an"`
	Description string  `json:"description"`
}

func GetAllGrade(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_grade, id_user, date_de_fin FROM GRADE")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var grades []Grade
	for rows.Next() {
		var grade Grade
		err := rows.Scan(&grade.ID, &grade.ID_Grade, &grade.ID_User, &grade.DateDeFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		grades = append(grades, grade)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(grades)
}

func GetAllTypeGrade(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, name, prix_mois, prix_an, description FROM TYPE_GRADE")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var typeGrades []TypeGrade
	for rows.Next() {
		var typeGrade TypeGrade
		err := rows.Scan(&typeGrade.ID, &typeGrade.Name, &typeGrade.PrixMois, &typeGrade.PrixAn, &typeGrade.Description)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		typeGrades = append(typeGrades, typeGrade)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(typeGrades)
}

func GetValidGrades(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_grade, id_user, date_de_fin FROM GRADE")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var validGrades []Grade
	now := time.Now()

	for rows.Next() {
		var grade Grade
		err := rows.Scan(&grade.ID, &grade.ID_Grade, &grade.ID_User, &grade.DateDeFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}

		dateDeFin, err := time.Parse("2006-01-02", grade.DateDeFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}

		if dateDeFin.After(now) {
			validGrades = append(validGrades, grade)
		}
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(validGrades)
}

func GetInvalidGrades(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_grade, id_user, date_de_fin FROM GRADE")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var invalidGrades []Grade
	now := time.Now()

	for rows.Next() {
		var grade Grade
		err := rows.Scan(&grade.ID, &grade.ID_Grade, &grade.ID_User, &grade.DateDeFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}

		dateDeFin, err := time.Parse("2006-01-02", grade.DateDeFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}

		if !dateDeFin.After(now) {
			invalidGrades = append(invalidGrades, grade)
		}
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(invalidGrades)
}

func GetGradebyUser(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	gradeUser := params["user"]

	rows, err := db.Query("SELECT id, id_grade, id_user, date_de_fin FROM GRADE WHERE ID_USER = ?", gradeUser)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var grades []Grade
	for rows.Next() {
		var grade Grade
		err := rows.Scan(&grade.ID, &grade.ID_Grade, &grade.ID_User, &grade.DateDeFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		grades = append(grades, grade)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(grades)
}

func GetGradebyGrade(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	gradeGrade := params["grade"]

	rows, err := db.Query("SELECT id, id_grade, id_user, date_de_fin FROM GRADE WHERE ID_Grade = ?", gradeGrade)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var grades []Grade
	for rows.Next() {
		var grade Grade
		err := rows.Scan(&grade.ID, &grade.ID_Grade, &grade.ID_User, &grade.DateDeFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		grades = append(grades, grade)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(grades)
}

func GetTypeGradeByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	TypeGradeId := params["id"]

	row := db.QueryRow("SELECT id, name, prix_mois, prix_an, description FROM TYPE_GRADE WHERE ID = ?", TypeGradeId)

	var typeGrade TypeGrade

	err := row.Scan(&typeGrade.ID,&typeGrade.Name,&typeGrade.PrixMois,&typeGrade.PrixAn,&typeGrade.Description)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(typeGrade)
}
