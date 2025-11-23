
  <style>
      :root {
          --background-color: #f0f2f5;
          --card-background: #ffffff;
          --text-color: #333333;
          --secondary-text-color: #666666;
          --border-color: #e0e0e0;
          --accent-color: #4a90e2; /* A soft blue for links/hover */
          --shadow-color: rgba(0, 0, 0, 0.08);
          --hover-shadow-color: rgba(0, 0, 0, 0.15);
      }

      .container{
        width: 100%;
        padding: 20px;
        text-align: center;
      }

      h1 {
          color: var(--text-color);
          margin-bottom: 40px;
          font-size: 2.5em;
          font-weight: 700;
      }

      .portal-grid {
          display: flex;
          flex-wrap: wrap;
          gap: 25px;
          justify-content: center;
          align-items: center;
      }

      .portal-item {
          background-color: var(--card-background);
          border: 1px solid var(--border-color);
          border-radius: 12px;
          padding: 30px;
          text-decoration: none;
          color: var(--text-color);
          display: flex;
          flex-direction: column;
          align-items: center;
          text-align: center;
          box-shadow: 0 4px 12px var(--shadow-color);
          transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
          height: 250px;
          width: 500px;
      }

      .portal-item:hover {
          transform: translateY(-5px);
          box-shadow: 0 8px 20px var(--hover-shadow-color);
      }

      .portal-item svg {
          width: 48px;
          height: 48px;
          color: var(--accent-color);
          margin-bottom: 20px;
      }

      .portal-item h2 {
          font-size: 1.5em;
          margin-top: 0;
          margin-bottom: 10px;
          color: var(--text-color);
          font-weight: 600;
      }

      .portal-item p {
          font-size: 1em;
          color: var(--secondary-text-color);
          line-height: 1.5;
          flex-grow: 1;
      }

      @media (max-width: 768px) {
          h1 {
              font-size: 2em;
              margin-bottom: 30px;
          }

          .portal-grid {
              grid-template-columns: 1fr;
              gap: 20px;
          }

          .portal-item {
              padding: 25px;
          }
      }

      @media (max-width: 480px) {
          h1 {
              font-size: 1.8em;
              margin-bottom: 25px;
          }

          .portal-item h2 {
              font-size: 1.3em;
          }

          .portal-item p {
              font-size: 0.95em;
          }
      }
  </style>

 <div class="container">
      <h1>Bienvenue sur votre Portail Le Vendelais</h1>
      <div class="portal-grid">
          <a href="https://nextcloud.levendelaiscinema.fr" class="portal-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cloud"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>
              <h2>Espace Cloud (FICHIERS)</h2>
              <p>Accédez à vos fichiers, calendriers et contacts en toute sécurité, où que vous soyez.</p>
          </a>

          <a href="https://gestion.levendelaiscinema.fr" class="portal-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20A14.5 14.5 0 0 0 12 2"/><path d="M2 12h20"/></svg>
              <h2>Gestion du site</h2>
              <p>Gérez le contenu, les films, évènements, bénévoles, ainsi que tous les paramètres du site.</p>
          </a>

          <a href="https://gestion.levendelaiscinema.fr/affiches" class="portal-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-film"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>
              <h2>Gestion des affiches</h2>
              <p>Archivez et vendez les affiches de cinéma.</p>
          </a>

          <a href="https://tutos.levendelaiscinema.fr" class="portal-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
              <h2>Tutoriels</h2>
              <p>Accédez à des guides et des tutoriels pour maîtriser les métiers du cinéma et les espaces du site internet.</p>
          </a>

          <!--<a href="https://gestion.levendelaiscinema.fr/webmaster" class="portal-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
              <h2>Webmaster</h2>
              <p>Accédez aux outils avancés pour l'administration technique et la maintenance du site.</p>
          </a>-->
      </div>
</div>