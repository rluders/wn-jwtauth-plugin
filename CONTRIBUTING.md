# Contributing

## Commit Convention

This project uses [Conventional Commits](https://www.conventionalcommits.org/).
Release Please reads commits on `main` and creates Release PRs automatically.

### Format

```
<type>(<optional scope>): <description>

[optional body]

[optional footer(s)]
```

### Types and version bumps

| Type | What it is | Version bump |
|------|-----------|--------------|
| `feat` | New feature | **Minor** (`1.5.0` → `1.6.0`) |
| `fix` | Bug fix | **Patch** (`1.5.0` → `1.5.1`) |
| `perf` | Performance improvement | Patch |
| `deps` | Dependency update | Patch |
| `refactor` | Internal refactor, no behaviour change | None (hidden) |
| `docs` | Documentation only | None (hidden) |
| `test` | Test only | None (hidden) |
| `chore` | Tooling, CI | None (hidden) |

A **breaking change** (major bump) is signalled with `!` after the type
or a `BREAKING CHANGE:` footer:

```
feat!: remove /api/auth/token-refresh alias

BREAKING CHANGE: endpoint renamed to /api/auth/refresh-token
```

### Examples

```
feat: add logout endpoint POST /api/auth/logout
fix: return 401 instead of 500 on expired token
deps: update php-open-source-saver/jwt-auth to ^2.2
docs: document custom claims event in README
```

## Running Tests

Requires [Podman](https://podman.io/) or Docker.

```bash
# Build image and run full suite
make test

# Unit tests only
make test-unit

# Feature tests only
make test-feature

# Drop into the container shell for debugging
make shell
```

## Pull Request Flow

1. Fork and create a branch from `main`
2. Write tests for your change
3. Ensure `make test` passes
4. Open a PR with a Conventional Commit title

Release Please will pick up your commits and open a Release PR automatically
when merged. Merging the Release PR publishes the GitHub Release and notifies
Packagist.

## Required GitHub Secrets

For the release workflow to notify Packagist, add these in the repo settings:

- `PACKAGIST_USERNAME` — your Packagist account username
- `PACKAGIST_TOKEN`   — a Packagist API token with update permissions
