<template>
    <div class="correction-container" style="padding: 20px; min-height: 100vh; background: #f8f9fa;">
      <!-- Loading State -->
      <div v-if="loading" class="loading-screen" style="padding: 60px; text-align: center;">
        <div style="padding: 20px; font-size: 18px; color: #7f8c8d;">
          Chargement de la correction...
        </div>
      </div>
  
      <!-- Error State -->
      <div v-else-if="error" class="error-screen" style="padding: 60px; text-align: center; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="padding: 20px; color: #e74c3c; font-size: 18px; font-weight: 500;">
          ❌ {{ error }}
        </div>
        <button 
          @click="goBack"
          style="padding: 12px 24px; margin-top: 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;"
        >
          Retour
        </button>
      </div>
  
      <!-- Correction Content -->
      <div v-else class="correction-content">
        <!-- Header -->
        <div class="correction-header" style="padding: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: center;">
          <h1 style="padding: 0 0 15px 0; margin: 0; color: #2c3e50; font-size: 28px;">📝 Correction de l'évaluation</h1>
          <h2 style="padding: 0 0 20px 0; margin: 0; color: #3498db; font-size: 20px;">{{ evaluation.titre }}</h2>
          
          <!-- Informations du candidat -->
          <div class="candidate-info" style="padding: 20px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
            <div class="candidate-details" style="display: flex; justify-content: center; align-items: center; gap: 30px; flex-wrap: wrap;">
              <div class="candidate-photo" style="flex-shrink: 0;">
                <div class="photo-container" style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; border: 3px solid #3498db; background: #ecf0f1; display: flex; align-items: center; justify-content: center;">
                  <img 
                    v-if="seminaristeInfo.photo" 
                    :src="seminaristeInfo.photo" 
                    :alt="seminaristeInfo.nom + ' ' + seminaristeInfo.prenom"
                    style="width: 100%; height: 100%; object-fit: cover;"
                  />
                  <div v-else style="font-size: 32px; color: #7f8c8d;">👤</div>
                </div>
              </div>
              
              <div class="candidate-text" style="text-align: left;">
                <div style="padding: 4px 0; color: #2c3e50; font-weight: bold;">{{ seminaristeInfo.nom }} {{ seminaristeInfo.prenom }}</div>
                <div style="padding: 4px 0; color: #3498db; font-family: monospace;">{{ seminaristeInfo.matricule_seminaire }}</div>
              </div>
            </div>
          </div>
  
          <!-- Résumé des résultats -->
          <div class="results-summary" style="padding: 20px 0;">
            <div class="results-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">
              <div class="result-item" style="padding: 15px; background: #e8f5e8; border-radius: 8px; border: 2px solid #27ae60;">
                <div style="font-size: 20px; font-weight: bold; color: #27ae60;">{{ correctAnswers }}</div>
                <div style="font-size: 12px; color: #27ae60;">Bonnes réponses</div>
              </div>
              <div class="result-item" style="padding: 15px; background: #ffeaea; border-radius: 8px; border: 2px solid #e74c3c;">
                <div style="font-size: 20px; font-weight: bold; color: #e74c3c;">{{ wrongAnswers }}</div>
                <div style="font-size: 12px; color: #e74c3c;">Mauvaises réponses</div>
              </div>
              <div class="result-item" style="padding: 15px; background: #fff3cd; border-radius: 8px; border: 2px solid #f39c12;">
                <div style="font-size: 20px; font-weight: bold; color: #f39c12;">{{ unansweredQuestions }}</div>
                <div style="font-size: 12px; color: #f39c12;">Non répondues</div>
              </div>
              <div class="result-item" style="padding: 15px; background: #f8f9fa; border-radius: 8px; border: 2px solid #3498db;">
                <div style="font-size: 20px; font-weight: bold; color: #3498db;">{{ sessionData.score_pourcentage }}%</div>
                <div style="font-size: 12px; color: #3498db;">Score final</div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Navigation rapide -->
        <div class="quick-navigation" style="padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: center;">
          <h3 style="padding: 0 0 15px 0; margin: 0; color: #2c3e50; font-size: 16px;">Navigation rapide :</h3>
          <div class="nav-buttons" style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center;">
            <button 
              v-for="(question, index) in questions" 
              :key="index"
              @click="scrollToQuestion(index)"
              class="nav-btn"
              style="padding: 8px 12px; border: 2px solid; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 500; min-width: 40px;"
              :style="getNavigationButtonStyle(index)"
            >
              {{ index + 1 }}
            </button>
          </div>
        </div>
  
        <!-- Questions et corrections -->
        <div class="questions-correction">
          <div 
            v-for="(question, index) in questions" 
            :key="index"
            :ref="`question-${index}`"
            class="question-correction-card"
            style="padding: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;"
          >
            <!-- En-tête de la question -->
            <div class="question-header" style="padding: 0 0 20px 0; border-bottom: 2px solid #ecf0f1; margin-bottom: 25px;">
              <div class="question-number-status" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div class="question-number" style="padding: 8px 16px; background: #3498db; color: white; border-radius: 20px; font-weight: bold;">
                  Question {{ index + 1 }} / {{ questions.length }}
                </div>
                <div class="question-status" :style="getQuestionStatusStyle(index)">
                  {{ getQuestionStatusText(index) }}
                </div>
              </div>
              
              <h3 style="padding: 0; margin: 0; color: #2c3e50; font-size: 18px; line-height: 1.4;">
                {{ question.question }}
              </h3>
              
              <div class="question-points" style="padding: 10px 0 0 0; color: #7f8c8d; font-size: 14px;">
                Points : {{ question.points }}
              </div>
            </div>
  
            <!-- Options de réponse -->
            <div class="answers-comparison" style="padding: 0;">
              <div 
                v-for="option in ['A', 'B', 'C', 'D']" 
                :key="option"
                class="answer-option-comparison"
                style="padding: 15px 0;"
              >
                <div 
                  class="answer-content"
                  style="padding: 15px 20px; border: 2px solid; border-radius: 8px; display: flex; align-items: flex-start; gap: 15px;"
                  :style="getAnswerOptionStyle(index, option)"
                >
                  <!-- Indicateur de statut -->
                  <div class="answer-indicator" style="flex-shrink: 0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                    <span :style="getAnswerIndicatorStyle(index, option)">
                      {{ getAnswerIndicator(index, option) }}
                    </span>
                  </div>
  
                  <!-- Contenu de la réponse -->
                  <div class="option-content" style="flex: 1;">
                    <div class="option-letter" style="padding: 0 0 5px 0; font-weight: bold; font-size: 16px;" :style="getOptionLetterStyle(index, option)">
                      {{ option }}.
                    </div>
                    <div class="option-text" style="padding: 0; line-height: 1.4;" :style="getOptionTextStyle(index, option)">
                      {{ question[`reponse_${option.toLowerCase()}`] }}
                    </div>
                  </div>
  
                  <!-- Labels explicatifs -->
                  <div class="answer-labels" style="flex-shrink: 0; display: flex; flex-direction: column; gap: 5px;">
                    <span v-if="isCorrectAnswer(index, option)" class="correct-label" style="padding: 4px 8px; background: #27ae60; color: white; border-radius: 12px; font-size: 12px; font-weight: bold;">
                      ✓ Correcte
                    </span>
                    <span v-if="isUserAnswer(index, option)" class="user-label" style="padding: 4px 8px; background: #3498db; color: white; border-radius: 12px; font-size: 12px; font-weight: bold;">
                      👤 Votre choix
                    </span>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Explication de la réponse (si disponible) -->
            <div v-if="question.explication" class="answer-explanation" style="padding: 20px; background: #f8f9fa; border-left: 4px solid #3498db; border-radius: 0 8px 8px 0; margin-top: 20px;">
              <h4 style="padding: 0 0 10px 0; margin: 0; color: #2c3e50; font-size: 16px;">💡 Explication :</h4>
              <p style="padding: 0; margin: 0; color: #2c3e50; line-height: 1.6;">{{ question.explication }}</p>
            </div>
          </div>
        </div>
  
        <!-- Actions finales -->
        <div class="final-actions" style="padding: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; margin-top: 20px;">
          <h3 style="padding: 0 0 20px 0; margin: 0; color: #2c3e50;">Actions</h3>
          <div class="action-buttons" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <button 
              @click="printCorrection"
              style="padding: 12px 24px; background: #9b59b6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
            >
              🖨️ Imprimer
            </button>
            <button 
              @click="downloadPDF"
              style="padding: 12px 24px; background: #e67e22; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
            >
              📄 Télécharger PDF
            </button>
            <button 
              @click="goBack"
              style="padding: 12px 24px; background: #3498db; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
            >
              ← Retour aux résultats
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'EvaluationCorrection',
    data() {
      return {
        loading: true,
        error: null,
        evaluationId: null,
        matricule: null,
        sessionId: null,
        
        // Données chargées
        evaluation: {},
        questions: [],
        userAnswers: [],
        sessionData: {},
        seminaristeInfo: {},
        
        // Statistiques calculées
        correctAnswers: 0,
        wrongAnswers: 0,
        unansweredQuestions: 0
      }
    },
    
    mounted() {
      this.parseUrlParams();
      this.loadCorrectionData();
    },
    
    methods: {
      parseUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        this.evaluationId = urlParams.get('evaluation_id');
        this.matricule = urlParams.get('matricule');
        this.sessionId = urlParams.get('session_id');
        
        if (!this.evaluationId || !this.matricule) {
          this.error = 'Paramètres manquants dans l\'URL';
        }
      },
      
      async loadCorrectionData() {
        if (this.error) return;
        
        try {
          const response = await fetch('https://sogetrag.com/api/evaluations-api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              action: 'get_correction',
              evaluation_id: this.evaluationId,
              matricule: this.matricule,
              session_id: this.sessionId
            })
          });
  
          const data = await response.json();
          console.log('📋 Correction data response:', data);
  
          if (data.success) {
            this.evaluation = data.evaluation;
            this.questions = data.questions;
            this.userAnswers = data.user_answers;
            this.sessionData = data.session_data;
            this.seminaristeInfo = data.seminariste_info;
            
            this.calculateStatistics();
          } else {
            this.error = data.error || 'Erreur lors du chargement de la correction';
          }
        } catch (error) {
          console.error('💥 Load correction error:', error);
          this.error = 'Erreur de connexion: ' + error.message;
        } finally {
          this.loading = false;
        }
      },
      
      calculateStatistics() {
        let correct = 0;
        let wrong = 0;
        let unanswered = 0;
        
        this.questions.forEach((question, index) => {
          const userAnswer = this.userAnswers[index];
          const correctAnswer = question.bonne_reponse;
          
          if (!userAnswer || userAnswer === '') {
            unanswered++;
          } else if (userAnswer === correctAnswer) {
            correct++;
          } else {
            wrong++;
          }
        });
        
        this.correctAnswers = correct;
        this.wrongAnswers = wrong;
        this.unansweredQuestions = unanswered;
      },
      
      getNavigationButtonStyle(index) {
        const userAnswer = this.userAnswers[index];
        const correctAnswer = this.questions[index].bonne_reponse;
        
        if (!userAnswer || userAnswer === '') {
          return 'border-color: #f39c12; background: #fff3cd; color: #f39c12;';
        } else if (userAnswer === correctAnswer) {
          return 'border-color: #27ae60; background: #d4edda; color: #27ae60;';
        } else {
          return 'border-color: #e74c3c; background: #f8d7da; color: #e74c3c;';
        }
      },
      
      getQuestionStatusStyle(index) {
        const userAnswer = this.userAnswers[index];
        const correctAnswer = this.questions[index].bonne_reponse;
        
        if (!userAnswer || userAnswer === '') {
          return 'padding: 8px 16px; background: #f39c12; color: white; border-radius: 20px; font-weight: bold;';
        } else if (userAnswer === correctAnswer) {
          return 'padding: 8px 16px; background: #27ae60; color: white; border-radius: 20px; font-weight: bold;';
        } else {
          return 'padding: 8px 16px; background: #e74c3c; color: white; border-radius: 20px; font-weight: bold;';
        }
      },
      
      getQuestionStatusText(index) {
        const userAnswer = this.userAnswers[index];
        const correctAnswer = this.questions[index].bonne_reponse;
        
        if (!userAnswer || userAnswer === '') {
          return '⚠️ Non répondue';
        } else if (userAnswer === correctAnswer) {
          return '✅ Correcte';
        } else {
          return '❌ Incorrecte';
        }
      },
      
      getAnswerOptionStyle(questionIndex, option) {
        const userAnswer = this.userAnswers[questionIndex];
        const correctAnswer = this.questions[questionIndex].bonne_reponse;
        
        if (option === correctAnswer && option === userAnswer) {
          // Bonne réponse choisie par l'utilisateur
          return 'border-color: #27ae60; background: #d4edda;';
        } else if (option === correctAnswer) {
          // Bonne réponse non choisie
          return 'border-color: #27ae60; background: #f8fff8;';
        } else if (option === userAnswer) {
          // Mauvaise réponse choisie par l'utilisateur
          return 'border-color: #e74c3c; background: #f8d7da;';
        } else {
          // Option normale
          return 'border-color: #ecf0f1; background: white;';
        }
      },
      
      getAnswerIndicatorStyle(questionIndex, option) {
        const userAnswer = this.userAnswers[questionIndex];
        const correctAnswer = this.questions[questionIndex].bonne_reponse;
        
        if (option === correctAnswer && option === userAnswer) {
          return 'background: #27ae60; color: white;';
        } else if (option === correctAnswer) {
          return 'background: #27ae60; color: white;';
        } else if (option === userAnswer) {
          return 'background: #e74c3c; color: white;';
        } else {
          return 'background: #ecf0f1; color: #7f8c8d;';
        }
      },
      
      getAnswerIndicator(questionIndex, option) {
        const userAnswer = this.userAnswers[questionIndex];
        const correctAnswer = this.questions[questionIndex].bonne_reponse;
        
        if (option === correctAnswer && option === userAnswer) {
          return '✓';
        } else if (option === correctAnswer) {
          return '✓';
        } else if (option === userAnswer) {
          return '✗';
        } else {
          return option;
        }
      },
      
      getOptionLetterStyle(questionIndex, option) {
        const userAnswer = this.userAnswers[questionIndex];
        const correctAnswer = this.questions[questionIndex].bonne_reponse;
        
        if (option === correctAnswer) {
          return 'color: #27ae60;';
        } else if (option === userAnswer) {
          return 'color: #e74c3c;';
        } else {
          return 'color: #3498db;';
        }
      },
      
      getOptionTextStyle(questionIndex, option) {
        const userAnswer = this.userAnswers[questionIndex];
        const correctAnswer = this.questions[questionIndex].bonne_reponse;
        
        if (option === correctAnswer) {
          return 'color: #27ae60; font-weight: 500;';
        } else if (option === userAnswer) {
          return 'color: #e74c3c;';
        } else {
          return 'color: #2c3e50;';
        }
      },
      
      isCorrectAnswer(questionIndex, option) {
        return option === this.questions[questionIndex].bonne_reponse;
      },
      
      isUserAnswer(questionIndex, option) {
        return option === this.userAnswers[questionIndex];
      },
      
      scrollToQuestion(index) {
        const element = this.$refs[`question-${index}`];
        if (element && element[0]) {
          element[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      },
      
      printCorrection() {
        window.print();
      },
      
      downloadPDF() {
        // Implémentation future pour générer un PDF
        alert('Fonctionnalité PDF en cours de développement');
      },
      
      goBack() {
        window.close();
      }
    }
  }
  </script>
  
  <style>
  @media print {
    .final-actions,
    .quick-navigation {
      display: none !important;
    }
    
    .question-correction-card {
      page-break-inside: avoid;
      margin-bottom: 30px !important;
    }
  }
  </style>
  