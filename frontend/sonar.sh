#!/usr/bin/env bash
set -euo pipefail

# === Scan sonar-scanner ===
# IMPORTANT : npm run test:coverage MUST be run first

ENVIRONMENT="${1:-}"
echo "[INFO] Environnement: ${ENVIRONMENT}"

# === Mapping from .env var names to sonar args ===
declare -A MAP

if [[ "$ENVIRONMENT" == "dev" ]]; then
  MAP[DEV_SONAR_TOKEN]="sonar.token"
  MAP[DEV_SONAR_ORGANIZATION]="sonar.organization"
  MAP[DEV_SONAR_PROJECT_KEY]="sonar.projectKey"
  MAP[DEV_SONAR_PROJECT_NAME]="sonar.projectName"
  MAP[DEV_SONAR_HOST_URL]="sonar.host.url"
else
  MAP[SONAR_TOKEN]="sonar.token"
  MAP[SONAR_ORGANIZATION]="sonar.organization"
  MAP[SONAR_PROJECT_KEY]="sonar.projectKey"
  MAP[SONAR_PROJECT_NAME]="sonar.projectName"
  MAP[SONAR_HOST_URL]="sonar.host.url"
fi

SONAR_ARGS="-Dsonar.verbose=true"

# === SAFE read .env ===
while IFS= read -r line || [[ -n "$line" ]]; do
  # cleanup CRLF
  line="${line//$'\r'/}"

  # ignore comments and empty lines
  [[ -z "$line" || "$line" =~ ^# ]] && continue

  # support "export VAR=value"
  line="${line#export }"

  key="${line%%=*}"
  value="${line#*=}"

  # trim
  key="$(echo "$key" | xargs)"
  value="$(echo "$value" | xargs)"

  # remove surrounding quotes
  value="${value%\"}"
  value="${value#\"}"
  value="${value%\'}"
  value="${value#\'}"

  # branch support
  if [[ "$ENVIRONMENT" == "dev" && "$key" == "DEV_SONAR_SUPPORTS_BRANCH" ]]; then
    SONAR_SUPPORTS_BRANCH="$value"
  elif [[ "$ENVIRONMENT" != "dev" && "$key" == "SONAR_SUPPORTS_BRANCH" ]]; then
    SONAR_SUPPORTS_BRANCH="$value"
  fi

  mappedKey="${MAP[$key]:-}"
  if [[ -n "$mappedKey" ]]; then
    SONAR_ARGS+=" -D${mappedKey}=${value}"
  fi
done < .env.local

echo "FULL ARGS ${SONAR_ARGS}"

# === Get Git current branch ===
BRANCH_NAME="$(git rev-parse --abbrev-ref HEAD)"

# === Add branch to sonar args ONLY IF supported ===
if [[ "${SONAR_SUPPORTS_BRANCH:-false}" == "true" ]]; then
  SONAR_ARGS+=" -Dsonar.branch.name=${BRANCH_NAME}"
  echo "[INFO] Branch: ${BRANCH_NAME}"
fi

echo "[INFO] Launching Sonar with args:"
echo "       ${SONAR_ARGS}"

# === Scan sonar-scanner ===
# npx sonar-scanner ${SONAR_ARGS}
bash node_modules/sonar-scanner/bin/sonar-scanner ${SONAR_ARGS}

echo "[INFO] Run ended."
