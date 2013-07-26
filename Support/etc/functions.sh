#!/usr/bin/env bash
#
# functions.sh
# This file will hold all gerneric functions shared through-out TextMate's bundle
#

[[ -f "${TM_SUPPORT_PATH}/lib/bash_init.sh" ]] && . "${TM_SUPPORT_PATH}/lib/bash_init.sh"
[[ -f "${TM_SUPPORT_PATH}/lib/html.sh" ]] && . "${TM_SUPPORT_PATH}/lib/html.sh"

cd ${TM_PROJECT_DIRECTORY}

#
# Prototype common vars
#
BEHAT_ARG="${BEHAT_ARG:--f html}"
LINE_NUMBER=0
CURRENT_LINE=""
MAX_LINE_NUMBER=$(wc -l "${TM_FILEPATH}" | sed -e 's/^[[:space:]]*\([[:digit:]]*\).*/\1/')


get_line_at()
{
  CURRENT_LINE=$( cat "${TM_FILEPATH}" | head -n$1 | tail -n1 | sed -e 's/^[[:space:]]*//' )
}
is_scenario()
{
  # Default return code to 1
  exit_code=1
  # Return -1 if a line starts with tag
  if test "${CURRENT_LINE:0:1}" = "@"
  then
    exit_code=-1
  # Scanario found, exit with success
  elif test "${CURRENT_LINE:0:8}" = "Scenario"
  then
    exit_code=0
  fi
  return $exit_code;
}

is_not_valid()
{
  # DRY this check
  if [[ $1 -le 0 ]]
  then
    return 0
  fi
  if [[ $1 -ge $MAX_LINE_NUMBER ]]
  then
    return 0
  fi
  return 1
}
incr()
{
  ((LINE_NUMBER++))
}
decr()
{
  ((LINE_NUMBER--))
}

die_with_alert()
{
  d_arg='{informativeText="'$body'";messageTitle="'$1'";alertStyle="critical";}'
  "$DIALOG" -ep "$d_arg"
  exit 0
}

#
# Find Behat executable
#
if [[ -z "${TM_BEHAT}" ]] # Was this defined by TextMate, or local property? -- skip
then
  if [[ -f "./bin/behat" ]] # Look for local composer
  then
    TM_BEHAT="./bin/behat"
  elif [[ -f "$(which behat)" ]] # Respect system's environemnt PATH
  then
    TM_BEHAT="$(which behat)"
  else
    body='One of the following must be defined\n\n - TM_BEHAT environemnt variable\n - Run php composer.phar install in project directory\n - Ensure behat executable is located in PATH'
    re=$(die_with_alert "Unable to located Behat")
    exit 0
  fi
fi

#
# Find profiles
#
TM_BEHAT_PROFILE="${TM_BEHAT_PROFILE:-default}"