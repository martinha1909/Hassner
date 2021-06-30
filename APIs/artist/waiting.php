<div>
                <form action="../APIs/DisplayArtistSongsConnection.php" method="post">
                  <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" value = "Songs">
                </form>
              </div>

              <div>
                <form action="../APIs/DisplayArtistAlbumsConnection.php" method="post">
                  <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" value = "Albums/EP">
                </form>
              </div>

              <div>
                <a href="CreateSongView.php" class="btn btn-primary" role="button" aria-pressed="true">
                  Add Song(s)
                </a>
              </div>

              <div>
                <a href="CreateAlbumView.php" class="btn btn-primary" role="button" aria-pressed="true">
                  Add Album(s)
                </a>
              </div>

              <!-- logout button-->
              <div>
                <a href="ArtistWritePost.php" class="btn btn-primary" role="button" aria-pressed="true">
                  Add post(s)
                </a>
              </div>

              <div>
              <a class="dropdown-item" id="dashboard-hover" style="background-color: transparent;" href="login.php">Log out</a>
              </div>