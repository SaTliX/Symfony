  //
  // main.js
  //
  
  let questionsData = [
    {
      text: "where are Estiam ?",
      answers: [
        {
          text: "Saint Etienne",
          isCorrect: false
        },
        {
          text: "Lyon",
          isCorrect: true
        },
        {
          text: "Nice",
          isCorrect: false
        },
        {
          text: "Grenoble",
          isCorrect: false
        }
      ]
    },
    {
      text: "who are the hackthon examinator ?",
      answers: [
        {
          text: "Malek",
          isCorrect: true
        },
        {
          text: "John",
          isCorrect: false
        },
        {
          text: "Willy",
          isCorrect: false
        },
        {
          text: "I Don't know",
          isCorrect: false
        }
      ]
    },
    {
      text: "What is Symfony ?",
      answers: [
        {
          text: "an application",
          isCorrect: false
        },
        {
          text: 'a web Site"',
          isCorrect: false
        },
        {
          text: "A PHP Framwork",
          isCorrect: true
        }
      ]
    },
    {
      text: "In witch year was the first version of c++ released",
      answers: [
        {
          text: "1982",
          isCorrect: true
        },
        {
          text: "1995",
          isCorrect: false
        },
        {
          text: "1983",
          isCorrect: false
        },
        {
          text: "1985",
          isCorrect: false
        }
      ]
    },
    {
      text: "What is France capital ?",
      answers: [
        {
          text: "Lyon",
          isCorrect: true
        },
        {
          text: "Marseille",
          isCorrect: false
        },
        {
          text: "Paris",
          isCorrect: true
        },
        {
          text: "Lille",
          isCorrect: false
        }
      ]
    },
    {
      text: "How classroon has Estiam ?",
      answers: 
      [
        {
          text: "5 classroom",
          isCorrect: true
        },
        {
          text: "4 classroom",
          isCorrect: false
        },
        {
          text: "3 classroom",
          isCorrect: false
        },
        {
          text: "1 classroom",
          isCorrect: false
        }
      ]
    },
    {
      text: "choose into the follow answer the name of the most popular CMS !",
      answers: [
        {
          text: "Prestashop",
          isCorrect: false
        },
        {
          text: "wix",
          isCorrect: false
        },
        {
          text: "Wordpress",
          isCorrect: true
        },
        {
          text: "Symfony",
          isCorrect: false
        }
      ]
    },
    {
      text: "What is Spain capital ? // quelle est la capital de l'Espagne ?",
      answers: [
        {
          text: "Madrid",
          isCorrect: true
        },
        {
          text: "Monaco",
          isCorrect: false
        },
        {
          text: "Paris",
          isCorrect: false
        },
        {
          text: "Lille",
          isCorrect: false
        }
      ]
    },
    {
      text: "who is the recent european Union president ? // qui est l'actuel president de l'union europeen ?",
      answers: [
        {
          text: "Charles Michel",
          isCorrect: true
        },
        {
          text: "Emmanuel Macron",
          isCorrect: false
        },
        {
          text: "tom will",
          isCorrect: true
        },
        {
          text: "Charlis Marvey",
          isCorrect: false
        }
      ]
    },
    {
      text: "combien de classe compte possede le campus de Estiam Lyon ?",
      answers: [
        {
          text: "Lyon",
          isCorrect: true
        },
        {
          text: "Marseille",
          isCorrect: false
        },
        {
          text: "Paris",
          isCorrect: true
        },
        {
          text: "Lille",
          isCorrect: false
        }
      ]
    },
    {
      text: "How students has Estiam E2 of Lyon ?",
      answers: [
        {
          text: "11",
          isCorrect: true
        },
        {
          text: "10",
          isCorrect: false
        },
        {
          text: "13",
          isCorrect: false
        },
        {
          text: "18",
          isCorrect: false
        }
      ]
    },
    {
      text: "Pourquoi Etudier a Estiam ?",
      answers: [
        {
          text: "Formation de qualité et de haut niveau",
          isCorrect: true
        },
        {
          text: "ecole d'ingenieur",
          isCorrect: false
        },
        {
          text: "ecole public",
          isCorrect: false
        },
        {
          text: "ecole billingue",
          isCorrect: false
        }
      ]
    }
  ];
  
  // variables initialization
  let questions = [];
  let score = 0,
    answeredQuestions = 0;
  let appContainer = document.getElementById("questions-container");
  let scoreContainer = document.getElementById("score-container");
  scoreContainer.innerHTML = `Score: ${score}/${questionsData.length}`;
  
  /**
   * Shuffles array in place. ES6 version
   * @param {Array} arr items An array containing the items.
   */
  function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
  }
  
  shuffle(questionsData);
  
  // creating questions
  for (var i = 0; i < questionsData.length; i++) {
    let question = new Question({
      text: questionsData[i].text,
      answers: questionsData[i].answers
    });
  
    appContainer.appendChild(question.create());
    questions.push(question);
  }
  
  document.addEventListener("question-answered", ({ detail }) => {
    if (detail.answer.isCorrect) {
      score++;
    }
  
    answeredQuestions++;
    scoreContainer.innerHTML = `Score: ${score}/${questions.length}`;
    detail.question.disable();
  
    if (answeredQuestions == questions.length) {
      setTimeout(function () {
        alert(`Quiz completed! \nFinal score: ${score}/${questions.length}`);
      }, 100);
    }
  });
  
  console.log(questions, questionsData);