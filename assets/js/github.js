const username = "elifhirli";

function addSystemLog(statusClass, statusText, message) {
    const systemLog = document.getElementById("system-log");

    if (!systemLog) {
        return;
    }

    const inputLine = systemLog.querySelector(".terminal-line:last-child");
    const logLine = document.createElement("p");
    const status = document.createElement("span");

    logLine.className = "terminal-line";
    status.className = statusClass;
    status.textContent = `[${statusText}]`;

    logLine.append(status, ` ${message}`);
    systemLog.insertBefore(logLine, inputLine);
}

async function fetchGithubData() {

    try {

        const [userResponse, reposResponse] = await Promise.all([
            fetch(`https://api.github.com/users/${username}`),
            fetch(`https://api.github.com/users/${username}/repos?sort=updated&per_page=6`)
        ]);

        const data = await userResponse.json();
        const repos = await reposResponse.json();
        const repoCount = document.getElementById("repo-count");
        const githubUser = document.getElementById("github-user");
        const repoList = document.getElementById("repo-list");
        const lastCommit = document.getElementById("last-commit");

        if (!repoCount || !githubUser || !repoList) {
            return;
        }

        repoCount.textContent = `PUBLIC_REPOS: ${data.public_repos}`;

        githubUser.textContent = `USER: ${data.login.toUpperCase()}`;

        repoList.innerHTML = "";

        if (!Array.isArray(repos) || repos.length === 0) {
            repoList.textContent = "NO_PUBLIC_REPOS_FOUND";
            if (lastCommit) {
                lastCommit.textContent = "NO_PUBLIC_REPOS";
            }
            return;
        }

        const latestRepo = repos[0];

        if (lastCommit) {
            lastCommit.textContent = `FETCHING_${latestRepo.name.toUpperCase()}`;
        }

        try {
            const commitResponse = await fetch(
                `https://api.github.com/repos/${username}/${latestRepo.name}/commits?per_page=1`
            );
            const commits = await commitResponse.json();

            if (!commitResponse.ok || !Array.isArray(commits) || commits.length === 0) {
                throw new Error("Commit data unavailable");
            }

            if (lastCommit) {
                const commitMessage = commits[0].commit.message.split("\n")[0];
                const shortMessage = commitMessage.length > 32
                    ? `${commitMessage.slice(0, 32)}...`
                    : commitMessage;

                lastCommit.textContent = shortMessage.toUpperCase();
            }
        } catch (error) {
            console.error("GitHub Commit Error:", error);

            if (lastCommit) {
                lastCommit.textContent = "COMMIT_UNAVAILABLE";
            }

            addSystemLog("warn", "WARN", "GITHUB_COMMIT_SYNC_FAILED");
        }

        repos.forEach((repo) => {
            const repoLink = document.createElement("a");
            repoLink.href = repo.html_url;
            repoLink.target = "_blank";
            repoLink.rel = "noopener noreferrer";
            repoLink.className = "repo-item";

            const repoName = document.createElement("strong");
            repoName.textContent = repo.name.toUpperCase();

            const repoMeta = document.createElement("span");
            repoMeta.textContent = `${repo.language || "N/A"} // STARS: ${repo.stargazers_count}`;

            repoLink.append(repoName, repoMeta);

            repoList.appendChild(repoLink);
        });

        addSystemLog("ok", "OK", "GITHUB_SYNC_COMPLETE");

    } catch (error) {

        console.error("GitHub API Error:", error);
        addSystemLog("warn", "WARN", "GITHUB_SYNC_FAILED");

    }
}

document.addEventListener("DOMContentLoaded", fetchGithubData);
