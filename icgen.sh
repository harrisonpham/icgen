#!/usr/bin/env bash

set -eu

export SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
source "${SCRIPT_DIR}/third_party/shflags/shflags"

DEFINE_string 'input_path' '' 'Input file path.'
DEFINE_string 'input_pattern' '*.vp' 'Input file pattern.'
DEFINE_string 'output_extension' '.v' 'Output file extension.'
DEFINE_boolean 'dry_run' false 'Run dry run outputting commands.'

FLAGS "$@" || exit $?
eval set -- "${FLAGS_ARGV}"

CMD=""
if [ ${FLAGS_dry_run} -eq ${FLAGS_TRUE} ]; then
    CMD="echo "
fi

process_file() {
    ${CMD} "${SCRIPT_DIR}/src/main.php" --input "$1" --output "${1%.*}${FLAGS_output_extension}"
}

export -f process_file
export CMD
export FLAGS_output_extension
find "${FLAGS_input_path}" -iname "${FLAGS_input_pattern}" -exec bash -c 'process_file "$0"' {} \;
